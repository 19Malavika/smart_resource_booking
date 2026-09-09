<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Any authenticated user can view the list of bookings (typically restricted to their own or all for admins/managers via query scopes)
    }

    public function view(User $user, Booking $booking)
    {
        return $user->isAdmin() || $user->id === $booking->user_id || $user->id === $booking->resource->manager_id;
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create bookings
    }

    public function update(User $user, Booking $booking)
    {
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Expired bookings cannot be modified
        if ($booking->status === 'EXPIRED' || $booking->status === 'COMPLETED') {
            return false;
        }

        return in_array($booking->status, ['PENDING', 'APPROVED']);
    }

    public function cancel(User $user, Booking $booking)
    {
        // Admins can cancel any booking
        if ($user->isAdmin()) {
            return true;
        }

        // Resource Managers can cancel bookings for resources they manage
        if ($user->isResourceManager() && $booking->resource->manager_id === $user->id) {
            return true;
        }

        // Employees can only cancel their own bookings
        if ($user->id !== $booking->user_id) {
            return false;
        }

        // Completed bookings cannot be cancelled
        if ($booking->status === 'COMPLETED' || $booking->status === 'EXPIRED') {
            return false;
        }

        // Pending bookings can always be cancelled by the owner
        if ($booking->status === 'PENDING') {
            return true;
        }

        // Approved bookings can be cancelled only before a defined cutoff by the owner
        if ($booking->status === 'APPROVED') {
            $cutoffHours = config('booking.cancellation_cutoff_hours', 24);
            $cutoffTime = $booking->start_datetime->copy()->subHours($cutoffHours);
            
            return now()->lessThan($cutoffTime);
        }

        return false;
    }

    public function approve(User $user, Booking $booking)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isResourceManager() && $booking->resource->manager_id === $user->id;
    }

    public function reject(User $user, Booking $booking)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isResourceManager() && $booking->resource->manager_id === $user->id;
    }

    public function override(User $user)
    {
        return $user->isAdmin();
    }
}
