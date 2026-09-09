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
            
            // Lock the resource for update to prevent concurrent booking conflicts
            $resource = Resource::lockForUpdate()->findOrFail($data['resource_id']);

            // Parse base dates
            $baseStart = Carbon::parse($data['start_datetime']);
            $baseEnd = Carbon::parse($data['end_datetime']);
            $durationInSeconds = $baseEnd->diffInSeconds($baseStart);

            $isRecurring = !empty($data['is_recurring']) && $data['is_recurring'];
            $pattern = $data['recurring_pattern'] ?? 'NONE';
            $occurrencesCount = $data['recurring_occurrences'] ?? 1;

            if (!$isRecurring || $pattern === 'NONE') {
                $occurrencesCount = 1;
            }

            // Calculate all dates first
            $occurrences = [];
            $currentStart = $baseStart->copy();
            
            for ($i = 0; $i < $occurrencesCount; $i++) {
                if ($i > 0) {
                    if ($pattern === 'DAILY') {
                        $currentStart->addDay();
                    } elseif ($pattern === 'WEEKLY') {
                        $currentStart->addWeek();
                    } elseif ($pattern === 'WEEKDAYS') {
                        $currentStart->addDay();
                        while ($currentStart->isWeekend()) {
                            $currentStart->addDay();
                        }
                    }
                }

                $currentEnd = $currentStart->copy()->addSeconds($durationInSeconds);
                $occurrences[] = [
                    'start' => $currentStart->copy(),
                    'end' => $currentEnd->copy()
                ];
            }

            // 1. Check resource status & capacity once (applies to all)
            if ($resource->status !== 'AVAILABLE') {
                throw ValidationException::withMessages(['resource_id' => 'This resource is not currently available for booking.']);
            }
            if ($data['participants'] > $resource->capacity) {
                throw ValidationException::withMessages(['participants' => "The number of participants exceeds the resource capacity of {$resource->capacity}."]);
            }

            // 2. Determine Approval Requirement once
            $approvalType = $resource->approval_type !== 'NONE' ? $resource->approval_type : $resource->category->approval_type;
            $status = $approvalType === 'NONE' ? 'APPROVED' : 'PENDING';

            // Validate ALL occurrences
            foreach ($occurrences as $index => $occ) {
                try {
                    $this->validateWorkingHours($resource, $occ['start'], $occ['end']);
                    $this->validateHolidays($occ['start'], $occ['end']);
                    $this->validateMaintenance($resource, $occ['start'], $occ['end']);
                    $this->validateConflicts($resource, $occ['start'], $occ['end']);
                } catch (ValidationException $e) {
                    // Prepend the occurrence date to the error message so the user knows which one failed
                    $dateStr = $occ['start']->format('M j, Y');
                    $errors = $e->errors();
                    $modifiedErrors = [];
                    foreach ($errors as $field => $messages) {
                        $modifiedErrors[$field] = array_map(function($msg) use ($dateStr) {
                            return "Conflict on {$dateStr}: {$msg} (The entire recurring series was rejected.)";
                        }, $messages);
                    }
                    throw ValidationException::withMessages($modifiedErrors);
                }
            }

            // If we get here, all occurrences passed validation!
            $parentBooking = null;

            foreach ($occurrences as $index => $occ) {
                $booking = Booking::create([
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'start_datetime' => $occ['start'],
                    'end_datetime' => $occ['end'],
                    'purpose' => $data['purpose'],
                    'participants' => $data['participants'],
                    'priority' => $data['priority'] ?? 'NORMAL',
                    'notes' => $data['notes'] ?? null,
                    'status' => $status,
                    'approval_type' => $approvalType,
                    'approved_at' => $status === 'APPROVED' ? now() : null,
                    'recurring_pattern' => $isRecurring ? $pattern : 'NONE',
                    'parent_booking_id' => $parentBooking ? $parentBooking->id : null,
                ]);

                if ($index === 0) {
                    $parentBooking = $booking;
                }
            }

            return $parentBooking;
        });
    }

    public function validateWorkingHours(Resource $resource, Carbon $start, Carbon $end)
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

    public function validateHolidays(Carbon $start, Carbon $end)
    {
        $dateString = $start->format('Y-m-d');
        $holiday = Holiday::where('date', $dateString)->first();

        if ($holiday) {
            throw ValidationException::withMessages([
                'start_datetime' => "Bookings are not allowed on holidays ({$holiday->name})."
            ]);
        }
    }

    public function validateMaintenance(Resource $resource, Carbon $start, Carbon $end)
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

    public function validateAllRules(Resource $resource, array $data, Carbon $start, Carbon $end, ?int $excludeBookingId = null)
    {
        if ($resource->status !== 'AVAILABLE') {
            throw ValidationException::withMessages(['resource_id' => 'This resource is not currently available for booking.']);
        }
        if (isset($data['participants']) && $data['participants'] > $resource->capacity) {
            throw ValidationException::withMessages(['participants' => "The number of participants exceeds the resource capacity of {$resource->capacity}."]);
        }

        $this->validateWorkingHours($resource, $start, $end);
        $this->validateHolidays($start, $end);
        $this->validateMaintenance($resource, $start, $end);
        $this->validateConflicts($resource, $start, $end, $excludeBookingId);
    }

    /**
     * Check for conflicting bookings.
     * 
     * Time-Boundary Rule: End times are considered EXCLUSIVE.
     * A booking from 10:00 AM to 11:00 AM does NOT conflict with a booking from 11:00 AM to 12:00 PM.
     * This is enforced using strict inequality:
     * (Existing Start < New End) AND (Existing End > New Start)
     */
    public function validateConflicts(Resource $resource, Carbon $start, Carbon $end, ?int $excludeBookingId = null)
    {
        $query = Booking::where('resource_id', $resource->id)
            ->whereIn('status', ['APPROVED', 'PENDING'])
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'start_datetime' => 'The resource is already booked or pending approval for this time period.'
            ]);
        }
    }
}
