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

        $query = Booking::with(['resource.category']);

        // Employees see only their bookings, unless they are Admin/Manager
        // For simplicity of this basic flow, we will scope to user's bookings first.
        // If we want a separate view for managers, we might use ManagerController or BookingRequestController.
        if (!auth()->user()->isAdmin() && !auth()->user()->isResourceManager()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('start_datetime', 'desc');
        $bookings = $query->paginate(15);

        return view('bookings.index', compact('bookings'));
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
        return view('bookings.edit', compact('booking'));
    }

    public function update(UpdateBookingRequest $request, Booking $booking, BookingService $bookingService)
    {
        // For modification, we should ideally re-run validation. 
        // For this step, we will manually trigger conflict/availability check in the controller
        // or refactor the service to support update validation.
        
        try {
            // Re-validate against the service (passing the booking ID to exclude from conflict check)
            $bookingService->validateConflicts($booking->resource, \Carbon\Carbon::parse($request->start_datetime), \Carbon\Carbon::parse($request->end_datetime), $booking->id);
            
            $status = $booking->resource->approval_type === 'NONE' ? 'APPROVED' : 'PENDING';

            $booking->update(array_merge($request->validated(), [
                'status' => $status,
                'approved_at' => $status === 'APPROVED' ? now() : null,
            ]));

            return redirect()->route('bookings.show', $booking)->with('success', 'Booking updated successfully.');
            
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);
        
        // Optional cutoff check could go here
        
        $booking->update([
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking cancelled successfully.');
    }

    public function calendar()
    {
        // Load approved bookings to show on the calendar
        $bookings = Booking::with('resource')->whereIn('status', ['APPROVED', 'PENDING'])->get();
        
        $events = $bookings->map(function ($booking) {
            $color = $booking->status === 'APPROVED' ? '#067d62' : '#f3a847';
            return [
                'title' => $booking->resource->name . ' - ' . $booking->purpose,
                'start' => $booking->start_datetime,
                'end' => $booking->end_datetime,
                'color' => $color,
                'url' => route('bookings.show', $booking->id)
            ];
        });

        return view('bookings.calendar', compact('events'));
    }
}
