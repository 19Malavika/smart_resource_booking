<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Services\BookingService;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Booking::class);

        $query = Booking::with(['resource.category', 'user']);

        // Employees see only their bookings, unless they are Admin/Manager
        // For simplicity of this basic flow, we will scope to user's bookings first.
        // If we want a separate view for managers, we might use ManagerController or BookingRequestController.
        if (auth()->user()->isEmployee()) {
            $query->where('user_id', auth()->id());
        } elseif (auth()->user()->isResourceManager()) {
            $query->whereHas('resource', function($q) {
                $q->where('manager_id', auth()->id());
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id') && (auth()->user()->isAdmin() || auth()->user()->isResourceManager())) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('resource', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_datetime', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_datetime', '<=', $request->end_date);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $query->orderByRaw("
            CASE priority 
                WHEN 'URGENT' THEN 4 
                WHEN 'HIGH' THEN 3 
                WHEN 'NORMAL' THEN 2 
                WHEN 'LOW' THEN 1 
                ELSE 0 
            END DESC
        ")->orderBy('start_datetime', 'desc');
        $bookings = $query->paginate(15)->withQueryString();

        // Data for filter dropdowns
        $categories = ResourceCategory::orderBy('name')->get();
        $resources = Resource::orderBy('name')->get();
        $users = (auth()->user()->isAdmin() || auth()->user()->isResourceManager()) ? \App\Models\User::orderBy('name')->get() : collect();

        return view('bookings.index', compact('bookings', 'categories', 'resources', 'users'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Booking::class);
        
        $categories = ResourceCategory::all();
        $query = Resource::where('status', 'AVAILABLE');
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $resources = $query->get();
        $selectedResource = $request->filled('resource_id') ? Resource::find($request->resource_id) : null;

        return view('bookings.create', compact('categories', 'resources', 'selectedResource'));
    }

    public function store(StoreBookingRequest $request, BookingService $bookingService)
    {
        try {
            $booking = $bookingService->createBooking(
                auth()->user(),
                $request->validated()
            );

            $message = $booking->status === 'APPROVED' 
                ? 'Booking created and approved successfully.' 
                : 'Booking request submitted and is pending approval.';

            return redirect()->route('bookings.show', $booking)->with('success', $message);
            
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred while creating your booking. Please try again.')->withInput();
        }
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);
        $resources = Resource::where('status', 'AVAILABLE')->orderBy('name')->get();
        // If the current booking's resource is not 'AVAILABLE', we should still include it so the dropdown doesn't break
        if (!$resources->contains($booking->resource_id)) {
            $resources->push($booking->resource);
        }
        return view('bookings.edit', compact('booking', 'resources'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking, BookingService $bookingService)
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $booking, $bookingService) {
                $validated = $request->validated();
                
                // Determine if this is a major change (resource or time changed)
                $isMajorChange = $booking->resource_id != $validated['resource_id'] ||
                    $booking->start_datetime->format('Y-m-d H:i') !== \Carbon\Carbon::parse($validated['start_datetime'])->format('Y-m-d H:i') ||
                    $booking->end_datetime->format('Y-m-d H:i') !== \Carbon\Carbon::parse($validated['end_datetime'])->format('Y-m-d H:i');

                // Lock the requested resource to prevent concurrent overlapping updates
                $resource = Resource::lockForUpdate()->findOrFail($validated['resource_id']);
                
                $start = \Carbon\Carbon::parse($validated['start_datetime']);
                $end = \Carbon\Carbon::parse($validated['end_datetime']);

                // Perform FULL re-validation of all booking rules
                $bookingService->validateAllRules($resource, $validated, $start, $end, $booking->id);

                // Determine Approval Requirement
                $approvalType = $resource->approval_type !== 'NONE' 
                    ? $resource->approval_type 
                    : $resource->category->approval_type;

                // If it's an APPROVED booking and a major change was made, it requires re-approval (if the new resource requires approval)
                if ($booking->status === 'APPROVED' && $isMajorChange) {
                    $status = $approvalType === 'NONE' ? 'APPROVED' : 'PENDING';
                } elseif ($booking->status === 'PENDING') {
                    // Pending stays pending or auto-approves if the new resource doesn't need approval
                    $status = $approvalType === 'NONE' ? 'APPROVED' : 'PENDING';
                } else {
                    // Minor change on an APPROVED booking keeps it APPROVED
                    $status = $booking->status;
                }

                $booking->update(array_merge($validated, [
                    'status' => $status,
                    'approval_type' => $approvalType,
                    'approved_at' => $status === 'APPROVED' ? ($booking->approved_at ?? now()) : null,
                ]));
            });

            return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully.');
            
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->authorize('cancel', $booking);
        
        $booking->update([
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);

        \App\Events\BookingStatusUpdated::dispatch($booking, 'Your booking has been cancelled.', $booking->user_id);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking cancelled successfully.');
    }

    public function calendar(Request $request)
    {
        $resources = Resource::orderBy('name')->get();
        $selectedResourceId = $request->resource_id;

        $query = Booking::with('resource')->whereIn('status', ['APPROVED', 'PENDING']);
        
        if ($selectedResourceId) {
            $query->where('resource_id', $selectedResourceId);
        }

        $bookings = $query->get();
        
        $events = collect();

        // 1. Bookings
        foreach ($bookings as $booking) {
            $event = [
                'title' => $booking->resource->name . ' - ' . $booking->purpose,
                'start' => $booking->start_datetime,
                'end' => $booking->end_datetime,
                'color' => $booking->status === 'APPROVED' ? '#067d62' : '#f3a847',
            ];
            
            if (auth()->check() && auth()->user()->can('view', $booking)) {
                $event['url'] = route('bookings.show', $booking->id);
            }
            
            $events->push($event);
        }

        // 2. Maintenance
        $maintenanceQuery = \App\Models\ResourceMaintenance::with('resource');
        if ($selectedResourceId) {
            $maintenanceQuery->where('resource_id', $selectedResourceId);
        }
        $maintenances = $maintenanceQuery->get();
        foreach ($maintenances as $m) {
            $events->push([
                'title' => 'MAINTENANCE: ' . $m->resource->name,
                'start' => $m->start_datetime,
                'end' => $m->end_datetime,
                'color' => '#52525b',
            ]);
        }

        // 3. Holidays
        $holidays = \App\Models\Holiday::all();
        foreach ($holidays as $h) {
            $events->push([
                'title' => 'HOLIDAY: ' . $h->name,
                'start' => $h->date,
                'allDay' => true,
                'color' => '#7f1d1d',
                'display' => 'background',
            ]);
        }

        // 4. Business Hours
        $businessHours = [];
        if ($selectedResourceId) {
            $availabilities = \App\Models\ResourceAvailability::where('resource_id', $selectedResourceId)->get();
            $dayMap = [
                'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6,
            ];
            
            foreach ($availabilities as $avail) {
                if ($avail->is_available && $avail->start_time && $avail->end_time) {
                    $businessHours[] = [
                        'daysOfWeek' => [$dayMap[strtolower($avail->day_of_week)]],
                        'startTime' => \Carbon\Carbon::parse($avail->start_time)->format('H:i'),
                        'endTime' => \Carbon\Carbon::parse($avail->end_time)->format('H:i'),
                    ];
                }
            }
        }

        return view('bookings.calendar', compact('events', 'resources', 'selectedResourceId', 'businessHours'));
    }

    public function approve(Booking $booking)
    {
        $this->authorize('approve', $booking);

        if ($booking->status !== 'PENDING') {
            return redirect()->back()->withErrors('Only pending bookings can be approved.');
        }

        $booking->update([
            'status' => 'APPROVED',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        \App\Events\BookingStatusUpdated::dispatch($booking, 'Your booking has been approved.', $booking->user_id);

        return redirect()->back()->with('success', 'Booking approved successfully.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorize('reject', $booking);

        if ($booking->status !== 'PENDING') {
            return redirect()->back()->withErrors('Only pending bookings can be rejected.');
        }

        $booking->update([
            'status' => 'REJECTED',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        \App\Events\BookingStatusUpdated::dispatch($booking, 'Your booking has been rejected.', $booking->user_id);

        return redirect()->back()->with('success', 'Booking rejected successfully.');
    }
}
