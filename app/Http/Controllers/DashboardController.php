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
            $stats = [
                'total_users' => User::count(),
                'total_departments' => \App\Models\Department::count(),
                'total_resources' => Resource::count(),
                'total_categories' => ResourceCategory::count(),
                'pending_requests' => Booking::where('status', 'PENDING')->count(),
                'approved_bookings' => Booking::where('status', 'APPROVED')->count(),
                'cancelled_bookings' => Booking::where('status', 'CANCELLED')->count(),
                'maintenance_resources' => Resource::where('status', 'MAINTENANCE')->count(),
            ];

            $recent_bookings = Booking::with(['user', 'resource'])
                ->latest()
                ->limit(6)
                ->get();

            // Additional stats for chart etc can be calculated here
            $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
            $utilizationData = Booking::where('status', 'APPROVED')
                ->where('start_datetime', '>=', $sixMonthsAgo)
                ->get()
                ->groupBy(function ($booking) {
                    return $booking->start_datetime->format('M');
                })
                ->map(function ($group) {
                    return $group->count();
                });

            $chartLabels = collect([]);
            $chartValues = collect([]);
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('M');
                $chartLabels->push($month);
                $chartValues->push($utilizationData->get($month, 0));
            }

            return view('dashboard.admin', compact('stats', 'recent_bookings', 'chartLabels', 'chartValues'));
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
                ->where('status', 'PENDING')
                ->orderByRaw("
                    CASE priority
                        WHEN 'URGENT' THEN 4
                        WHEN 'HIGH' THEN 3
                        WHEN 'NORMAL' THEN 2
                        WHEN 'LOW' THEN 1
                        ELSE 0
                    END DESC
                ")
                ->oldest()
                ->get();

            $approvedBookings = Booking::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->where('status', 'APPROVED')
                ->latest()
                ->get();

            $upcomingBookings = Booking::with(['user', 'resource'])
                ->whereIn('resource_id', $resourceIds)
                ->where('status', 'APPROVED')
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime')
                ->limit(6)
                ->get();

            $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
            
            $utilizationData = Booking::whereIn('resource_id', $resourceIds)
                ->where('status', 'APPROVED')
                ->where('start_datetime', '>=', $sixMonthsAgo)
                ->get()
                ->groupBy(function ($booking) {
                    return $booking->start_datetime->format('M');
                })
                ->map(function ($group) {
                    return $group->count();
                });

            $chartLabels = collect([]);
            $chartValues = collect([]);
            
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('M');
                $chartLabels->push($month);
                $chartValues->push($utilizationData->get($month, 0));
            }

            return view('dashboard.manager', compact(
                'assignedResources',
                'pendingBookings',
                'approvedBookings',
                'upcomingBookings',
                'chartLabels',
                'chartValues'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | Employee Dashboard
        |--------------------------------------------------------------------------
        */

        $availableResourcesCount = Resource::where(
            'status',
            'AVAILABLE'
        )->count();

        $availableResourcesList = Resource::where('status', 'AVAILABLE')
            ->latest()
            ->limit(5)
            ->get();

        $myBookings = Booking::with('resource')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $pendingCount = $myBookings
            ->where('status', 'PENDING')
            ->count();

        $approvedCount = $myBookings
            ->where('status', 'APPROVED')
            ->count();

        $rejectedCount = $myBookings
            ->where('status', 'REJECTED')
            ->count();

        $upcomingBookings = Booking::with('resource')
            ->where('user_id', $user->id)
            ->where('status', 'APPROVED')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->limit(6)
            ->get();

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        
        $historyData = Booking::where('user_id', $user->id)
            ->where('start_datetime', '>=', $sixMonthsAgo)
            ->get()
            ->groupBy(function ($booking) {
                return $booking->start_datetime->format('M');
            })
            ->map(function ($group) {
                return $group->count();
            });

        $chartLabels = collect([]);
        $chartValues = collect([]);
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('M');
            $chartLabels->push($month);
            $chartValues->push($historyData->get($month, 0));
        }

        return view('dashboard.employee', compact(
            'availableResourcesCount',
            'availableResourcesList',
            'myBookings',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'upcomingBookings',
            'chartLabels',
            'chartValues'
        ));
    }
}