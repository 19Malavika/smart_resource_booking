<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'resource.category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('resource', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('resource_id')) {
            $query->where('resource_id', $request->resource_id);
        }

        if ($request->filled('date_from')) {
            $query->where('start_datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_datetime', '<=', $request->date_to . ' 23:59:59');
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'resource.category', 'approver']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function create()
    {
        // Admin doesn't usually create bookings, redirect to index
        return redirect()->route('admin.bookings.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.bookings.index');
    }

    public function edit(Booking $booking)
    {
        return redirect()->route('admin.bookings.show', $booking);
    }

    public function update(Request $request, Booking $booking)
    {
        $action = $request->input('action');

        if ($action === 'approve' && $booking->status === 'PENDING') {
            $oldStatus = $booking->status;
            $booking->update([
                'status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved_booking',
                'auditable_type' => Booking::class,
                'auditable_id' => $booking->id,
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => 'APPROVED'],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Booking approved successfully.');
        }

        if ($action === 'reject' && $booking->status === 'PENDING') {
            $request->validate(['rejection_reason' => 'required|string|max:500']);

            $oldStatus = $booking->status;
            $booking->update([
                'status' => 'REJECTED',
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected_booking',
                'auditable_type' => Booking::class,
                'auditable_id' => $booking->id,
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => 'REJECTED', 'reason' => $request->rejection_reason],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Booking rejected.');
        }

        if ($action === 'cancel' && in_array($booking->status, ['PENDING', 'APPROVED'])) {
            $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

            $oldStatus = $booking->status;
            $booking->update([
                'status' => 'CANCELLED',
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by admin',
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'cancelled_booking',
                'auditable_type' => Booking::class,
                'auditable_id' => $booking->id,
                'old_values' => ['status' => $oldStatus],
                'new_values' => ['status' => 'CANCELLED'],
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Booking cancelled.');
        }

        return back()->with('error', 'Invalid action for this booking status.');
    }

    public function destroy(Booking $booking)
    {
        return redirect()->route('admin.bookings.index')->with('error', 'Bookings cannot be deleted. Use cancel instead.');
    }
}
