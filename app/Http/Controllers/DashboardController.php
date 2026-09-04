<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */
        if ($user->isAdmin()) {
            $totalEmployees = User::where('role', 'employee')->count();

            $totalManagers = User::where('role', 'resource_manager')->count();

            $totalResources = Resource::count();

            $totalCategories = ResourceCategory::count();

            $totalBookings = Booking::count();

            $pendingBookings = Booking::where('status', 'pending')->count();

            $approvedBookings = Booking::where('status', 'approved')->count();

            $rejectedBookings = Booking::where('status', 'rejected')->count();

            $recentBookings = Booking::with(['user', 'resource'])
                ->latest()
                ->limit(6)
                ->get();

            return view('dashboard.admin', compact(
                'totalEmployees',
                'totalManagers',
                'totalResources',
                'totalCategories',
                'totalBookings',
                'pendingBookings',
                'approvedBookings',
                'rejectedBookings',
                'recentBookings'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | Resource Manager Dashboard
        |--------------------------------------------------------------------------
        */
        if ($user->isResourceManager()) {
            $assignedResources = Resource::where(
                'manager_id',
                $user->id
            )->count();

            $resourceIds = Resource::where(
                'manager_id',
                $user->id
            )->pluck('id');

            $pendingBookings = Booking::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->where('status', 'pending')
                ->latest()
                ->get();

            $approvedBookings = Booking::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->where('status', 'approved')
                ->latest()
                ->get();

            $upcomingBookings = Booking::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->where('status', 'approved')
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime')
                ->limit(6)
                ->get();

            return view('dashboard.manager', compact(
                'assignedResources',
                'pendingBookings',
                'approvedBookings',
                'upcomingBookings'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | Employee Dashboard
        |--------------------------------------------------------------------------
        */

        $availableResources = Resource::where(
            'status',
            'available'
        )->count();

        $myBookings = Booking::with('resource')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $pendingCount = $myBookings
            ->where('status', 'pending')
            ->count();

        $approvedCount = $myBookings
            ->where('status', 'approved')
            ->count();

        $rejectedCount = $myBookings
            ->where('status', 'rejected')
            ->count();

        $upcomingBookings = Booking::with('resource')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->limit(6)
            ->get();

        return view('dashboard.employee', compact(
            'availableResources',
            'myBookings',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'upcomingBookings'
        ));
    }
}