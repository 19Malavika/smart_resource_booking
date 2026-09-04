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
        return true;
    }

    public function update(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id && in_array($booking->status, ['PENDING', 'APPROVED']);
    }

    public function cancel(User $user, Booking $booking)
    {
        // Must be their own booking, and only if it's not completed/expired
        return $user->id === $booking->user_id && in_array($booking->status, ['PENDING', 'APPROVED']);
    }

    public function approve(User $user, Booking $booking)
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
