<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Holiday;
use App\Models\Resource;
use App\Models\ResourceAvailability;
use App\Models\ResourceMaintenance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Create a new booking request.
     */
    public function createBooking(User $user, array $data): Booking
    {
        return DB::transaction(function () use ($user, $data) {
            
            // For now we get the resource without a lock (we'll implement lockForUpdate in Phase 7)
            $resource = Resource::findOrFail($data['resource_id']);

            // Parse dates
            $start = Carbon::parse($data['start_datetime']);
            $end = Carbon::parse($data['end_datetime']);

            // 1. Check resource status
            if ($resource->status !== 'AVAILABLE') {
                throw ValidationException::withMessages([
                    'resource_id' => 'This resource is not currently available for booking.'
                ]);
            }

            // 2. Check capacity
            if ($data['participants'] > $resource->capacity) {
                throw ValidationException::withMessages([
                    'participants' => "The number of participants exceeds the resource capacity of {$resource->capacity}."
                ]);
            }

            // 3. Check working hours & working days
            $this->validateWorkingHours($resource, $start, $end);

            // 4. Check holidays
            $this->validateHolidays($start, $end);

            // 5. Check maintenance
            $this->validateMaintenance($resource, $start, $end);

            // 6. Check conflicts with existing approved bookings
            $this->validateConflicts($resource, $start, $end);

            // 7. Determine Approval Requirement
            // Categories can have an approval_type, but the Resource's approval_type overrides it if set.
            // For this logic, we use the Resource's approval type (it defaults to NONE)
            // Wait, we defined approval_type on both. Let's use the resource's approval type.
            $approvalType = $resource->approval_type;
            if ($approvalType === 'NONE') {
                $status = 'APPROVED';
            } else {
                $status = 'PENDING';
            }

            // 8. Create the booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'purpose' => $data['purpose'],
                'participants' => $data['participants'],
                'priority' => $data['priority'] ?? 'NORMAL',
                'notes' => $data['notes'] ?? null,
                'status' => $status,
                'approval_type' => $approvalType,
                'approved_at' => $status === 'APPROVED' ? now() : null,
            ]);

            return $booking;
        });
    }

    protected function validateWorkingHours(Resource $resource, Carbon $start, Carbon $end)
    {
        // Get configured availability for the day of the week
        $dayOfWeek = strtolower($start->format('l'));
        
        $availability = ResourceAvailability::where('resource_id', $resource->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        // If no explicit configuration is set, we might default to allowed or rejected.
        // For strictness, if availability rules exist for the resource, they must be met.
        $hasRules = ResourceAvailability::where('resource_id', $resource->id)->exists();
        
        if ($hasRules) {
            if (!$availability || !$availability->is_available) {
                throw ValidationException::withMessages([
                    'start_datetime' => "The resource is closed on {$dayOfWeek}s."
                ]);
            }

            // Check time boundaries
            $startTimeString = $start->format('H:i:s');
            $endTimeString = $end->format('H:i:s');

            if ($startTimeString < $availability->start_time || $endTimeString > $availability->end_time) {
                throw ValidationException::withMessages([
                    'start_datetime' => "Booking must be within allowed hours: {$availability->start_time} to {$availability->end_time}."
                ]);
            }
        }
    }

    protected function validateHolidays(Carbon $start, Carbon $end)
    {
        $dateString = $start->format('Y-m-d');
        $holiday = Holiday::where('date', $dateString)->first();

        if ($holiday) {
            throw ValidationException::withMessages([
                'start_datetime' => "Bookings are not allowed on holidays ({$holiday->name})."
            ]);
        }
    }

    protected function validateMaintenance(Resource $resource, Carbon $start, Carbon $end)
    {
        $conflict = ResourceMaintenance::where('resource_id', $resource->id)
            ->whereIn('status', ['SCHEDULED', 'ACTIVE'])
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'start_datetime' => 'The resource is scheduled for maintenance during this time period.'
            ]);
        }
    }

    public function validateConflicts(Resource $resource, Carbon $start, Carbon $end, ?int $excludeBookingId = null)
    {
        $query = Booking::where('resource_id', $resource->id)
            ->whereIn('status', ['APPROVED', 'PENDING']) // We could allow pending overlaps, but usually we reject them if another pending exists to prevent double pending. Let's reject if there is any overlapping approved or pending booking that isn't expired. Wait, the prompt says Priority can't overwrite approved. For now we prevent overlap with APPROVED.
            ->where('status', 'APPROVED') 
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_datetime' => 'The resource is already booked for this time period.'
            ]);
        }
    }
}
