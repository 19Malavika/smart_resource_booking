<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use App\Models\Department;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        // Booking Stats
        $totalBookings = Booking::where('start_datetime', '>=', $startDate)->count();
        $approvedBookings = Booking::where('status', 'APPROVED')->where('start_datetime', '>=', $startDate)->count();
        $rejectedBookings = Booking::where('status', 'REJECTED')->where('start_datetime', '>=', $startDate)->count();
        $cancelledBookings = Booking::where('status', 'CANCELLED')->where('start_datetime', '>=', $startDate)->count();
        $pendingBookings = Booking::where('status', 'PENDING')->where('start_datetime', '>=', $startDate)->count();

        // Top booked resources
        $topResources = Resource::withCount(['bookings' => function ($q) use ($startDate) {
            $q->where('start_datetime', '>=', $startDate);
        }])->orderByDesc('bookings_count')->limit(10)->get();

        // Top users
        $topUsers = User::withCount(['bookings' => function ($q) use ($startDate) {
            $q->where('start_datetime', '>=', $startDate);
        }])->orderByDesc('bookings_count')->limit(10)->get();

        // Bookings by department
        $departmentStats = Department::withCount(['employees' => function ($q) {
        }])->get()->map(function ($dept) use ($startDate) {
            $dept->booking_count = Booking::whereHas('user', fn($q) => $q->where('department_id', $dept->id))
                ->where('start_datetime', '>=', $startDate)->count();
            return $dept;
        })->sortByDesc('booking_count')->values();

        // Bookings by category
        $categoryStats = ResourceCategory::withCount(['resources'])->get()->map(function ($cat) use ($startDate) {
            $cat->booking_count = Booking::whereHas('resource', fn($q) => $q->where('category_id', $cat->id))
                ->where('start_datetime', '>=', $startDate)->count();
            return $cat;
        })->sortByDesc('booking_count')->values();

        return view('admin.reports.index', compact(
            'period', 'totalBookings', 'approvedBookings', 'rejectedBookings',
            'cancelledBookings', 'pendingBookings', 'topResources', 'topUsers',
            'departmentStats', 'categoryStats'
        ));
    }

    public function show($report)
    {
        return redirect()->route('admin.reports.index');
    }

    public function create() { return redirect()->route('admin.reports.index'); }
    public function store(Request $request) { return redirect()->route('admin.reports.index'); }
    public function edit($id) { return redirect()->route('admin.reports.index'); }
    public function update(Request $request, $id) { return redirect()->route('admin.reports.index'); }
    public function destroy($id) { return redirect()->route('admin.reports.index'); }
}
