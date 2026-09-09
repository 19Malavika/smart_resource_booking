<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Authorization
        if (!$currentUser->isAdmin()) {
            if ($currentUser->id !== $user->id) {
                abort(403, 'Unauthorized access to this profile.');
            }
        }

        $user->load(['department', 'manager', 'subordinates', 'bookings' => function($q) {
            $q->orderBy('start_datetime', 'desc')->take(10);
        }, 'bookings.resource']);

        $stats = [];
        
        // Employee Stats
        $stats['total_bookings'] = $user->bookings->count();
        $stats['pending_bookings'] = $user->bookings->where('status', 'PENDING')->count();
        $stats['approved_bookings'] = $user->bookings->where('status', 'APPROVED')->count();
        $stats['rejected_bookings'] = $user->bookings->where('status', 'REJECTED')->count();
        
        if ($user->isResourceManager()) {
            $managedResourceIds = Resource::where('manager_id', $user->id)->pluck('id');
            $stats['total_managed_resources'] = $managedResourceIds->count();
            
            $stats['pending_approvals'] = Booking::whereIn('resource_id', $managedResourceIds)
                                        ->where('status', 'PENDING')
                                        ->count();
            $stats['approved_requests'] = Booking::whereIn('resource_id', $managedResourceIds)
                                        ->where('status', 'APPROVED')
                                        ->count();
            $stats['rejected_requests'] = Booking::whereIn('resource_id', $managedResourceIds)
                                        ->where('status', 'REJECTED')
                                        ->count();
            
            $stats['recent_activity'] = Booking::whereIn('resource_id', $managedResourceIds)
                                        ->whereIn('status', ['approved', 'rejected'])
                                        ->orderBy('updated_at', 'desc')
                                        ->with(['user', 'resource'])
                                        ->take(5)
                                        ->get();
        }

        if ($user->isAdmin()) {
            $stats['total_users'] = User::count();
            $stats['total_employees'] = User::where('role', 'employee')->count();
            $stats['total_managers'] = User::where('role', 'resource_manager')->count();
            $stats['total_departments'] = Department::count();
            $stats['active_users'] = User::where('status', 'active')->count();
        }

        return view('users.show', compact('user', 'stats'));
    }
}
