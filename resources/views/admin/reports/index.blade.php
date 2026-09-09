@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div><h1 class="text-2xl font-bold text-zinc-50 mb-1">Reports</h1><p class="text-sm text-zinc-400">System analytics and booking reports</p></div>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex gap-2 items-center">
            <select name="period" onchange="this.form.submit()" class="px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Last Quarter</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Last Year</option>
            </select>
        </form>
    </div>

    <!-- Booking Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-4"><span class="text-xs text-zinc-500">Total Bookings</span><h3 class="text-2xl font-bold text-zinc-50 mt-1">{{ $totalBookings }}</h3></div>
        <div class="bg-zinc-950 rounded-xl border border-emerald-500/20 p-4"><span class="text-xs text-emerald-400/80">Approved</span><h3 class="text-2xl font-bold text-emerald-400 mt-1">{{ $approvedBookings }}</h3></div>
        <div class="bg-zinc-950 rounded-xl border border-amber-500/20 p-4"><span class="text-xs text-amber-400/80">Pending</span><h3 class="text-2xl font-bold text-amber-400 mt-1">{{ $pendingBookings }}</h3></div>
        <div class="bg-zinc-950 rounded-xl border border-red-500/20 p-4"><span class="text-xs text-red-400/80">Rejected</span><h3 class="text-2xl font-bold text-red-400 mt-1">{{ $rejectedBookings }}</h3></div>
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-4"><span class="text-xs text-zinc-500">Cancelled</span><h3 class="text-2xl font-bold text-zinc-400 mt-1">{{ $cancelledBookings }}</h3></div>
    </div>

    <div class="reveal-up grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Resources -->
        <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
            <h3 class="text-base font-semibold text-zinc-50 mb-4">Most Booked Resources</h3>
            <div class="space-y-3">
                @forelse($topResources->take(7) as $res)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-300 truncate mr-3">{{ $res->name }}</span>
                    <div class="flex items-center gap-2">
                        <div class="w-24 bg-zinc-800 rounded-full h-1.5"><div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $topResources->max('bookings_count') > 0 ? ($res->bookings_count / $topResources->max('bookings_count') * 100) : 0 }}%"></div></div>
                        <span class="text-xs font-mono text-zinc-400 w-8 text-right">{{ $res->bookings_count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-zinc-500">No data available.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
            <h3 class="text-base font-semibold text-zinc-50 mb-4">Most Active Users</h3>
            <div class="space-y-3">
                @forelse($topUsers->take(7) as $u)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-zinc-300 truncate mr-3">{{ $u->name }}</span>
                    <div class="flex items-center gap-2">
                        <div class="w-24 bg-zinc-800 rounded-full h-1.5"><div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $topUsers->max('bookings_count') > 0 ? ($u->bookings_count / $topUsers->max('bookings_count') * 100) : 0 }}%"></div></div>
                        <span class="text-xs font-mono text-zinc-400 w-8 text-right">{{ $u->bookings_count }}</span>
                    </div>
                </div>
                @empty
                <p class="text-sm text-zinc-500">No data available.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="reveal-up grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- By Department -->
        <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
            <h3 class="text-base font-semibold text-zinc-50 mb-4">Bookings by Department</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead><tr class="border-b border-zinc-800"><th class="py-2 font-medium text-zinc-400">Department</th><th class="py-2 font-medium text-zinc-400">Employees</th><th class="py-2 font-medium text-zinc-400 text-right">Bookings</th></tr></thead>
                    <tbody>
                        @forelse($departmentStats->take(8) as $dept)
                        <tr class="border-b border-zinc-800 last:border-0"><td class="py-2 text-zinc-200">{{ $dept->name }}</td><td class="py-2 text-zinc-400">{{ $dept->employees_count }}</td><td class="py-2 text-right text-zinc-300 font-mono">{{ $dept->booking_count }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-zinc-500 py-4">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- By Category -->
        <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
            <h3 class="text-base font-semibold text-zinc-50 mb-4">Bookings by Category</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead><tr class="border-b border-zinc-800"><th class="py-2 font-medium text-zinc-400">Category</th><th class="py-2 font-medium text-zinc-400">Resources</th><th class="py-2 font-medium text-zinc-400 text-right">Bookings</th></tr></thead>
                    <tbody>
                        @forelse($categoryStats->take(8) as $cat)
                        <tr class="border-b border-zinc-800 last:border-0"><td class="py-2 text-zinc-200">{{ $cat->name }}</td><td class="py-2 text-zinc-400">{{ $cat->resources_count }}</td><td class="py-2 text-right text-zinc-300 font-mono">{{ $cat->booking_count }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-zinc-500 py-4">No data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
