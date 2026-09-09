@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1">Admin Dashboard</h1>
            <p class="text-sm text-zinc-400">System overview and management console</p>
        </div>
        <div class="text-sm text-zinc-500">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="reveal-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <!-- Total Users -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-5 flex flex-col justify-between hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Total Users</span>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($stats['total_users']) }}</h3>
        </div>

        <!-- Total Departments -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-5 flex flex-col justify-between hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Total Departments</span>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($stats['total_departments']) }}</h3>
        </div>

        <!-- Total Resources -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-5 flex flex-col justify-between hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Total Resources</span>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($stats['total_resources']) }}</h3>
        </div>

        <!-- Resource Categories -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-5 flex flex-col justify-between hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Resource Categories</span>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($stats['total_categories']) }}</h3>
        </div>

        <!-- Pending Requests -->
        <div class="bg-zinc-950 rounded-xl border border-amber-500/20 p-5 flex flex-col justify-between hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-amber-400/80">Pending Requests</span>
                <svg class="w-5 h-5 text-amber-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-amber-400">{{ number_format($stats['pending_requests']) }}</h3>
        </div>

        <!-- Approved Bookings -->
        <div class="bg-zinc-950 rounded-xl border border-emerald-500/20 p-5 flex flex-col justify-between hover:border-emerald-500/40 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-emerald-400/80">Approved Bookings</span>
                <svg class="w-5 h-5 text-emerald-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-emerald-400">{{ number_format($stats['approved_bookings']) }}</h3>
        </div>

        <!-- Cancelled Bookings -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 p-5 flex flex-col justify-between hover:border-zinc-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-500">Cancelled Bookings</span>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-zinc-400">{{ number_format($stats['cancelled_bookings']) }}</h3>
        </div>

        <!-- Maintenance -->
        <div class="bg-zinc-950 rounded-xl border border-red-500/20 p-5 flex flex-col justify-between hover:border-red-500/40 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-red-400/80">Under Maintenance</span>
                <svg class="w-5 h-5 text-red-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-red-400">{{ number_format($stats['maintenance_resources']) }}</h3>
        </div>
    </div>

    <div class="reveal-up grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Booking Trend Chart & Utilization -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-semibold text-zinc-50">Booking Trend (6 Months)</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-medium text-zinc-400 hover:text-zinc-200">View All</a>
                </div>
                <div class="h-[250px]">
                    <canvas id="bookingTrendChart"></canvas>
                </div>
            </div>

            <!-- Recent Bookings Table -->
            <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-zinc-50">Recent Booking Requests</h3>
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300">Manage Requests</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-zinc-800">
                                <th class="py-3 font-medium text-zinc-400">Resource</th>
                                <th class="py-3 font-medium text-zinc-400">Employee</th>
                                <th class="py-3 font-medium text-zinc-400">Date & Time</th>
                                <th class="py-3 font-medium text-zinc-400">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_bookings as $booking)
                            <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                                <td class="py-3">
                                    <div class="font-medium text-zinc-200">{{ $booking->resource->name }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ $booking->resource->category->name }}</div>
                                </td>
                                <td class="py-3 text-zinc-300">
                                    {{ $booking->user->name }}
                                </td>
                                <td class="py-3">
                                    <div class="text-zinc-300">{{ $booking->start_datetime->format('M j, Y') }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ $booking->start_datetime->format('H:i') }} - {{ $booking->end_datetime->format('H:i') }}</div>
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusClasses = match(strtolower($booking->status)) {
                                            'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'rejected', 'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $statusClasses }}">
                                        {{ strtolower($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-zinc-500 py-6">No recent bookings.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Quick Actions & Secondary Cards -->
        <div class="flex flex-col gap-6">
            
            <a href="{{ route('utilization.index') }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-emerald-500/50 hover:bg-zinc-900/50 transition-all group block">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-zinc-400 group-hover:text-zinc-300">Overall Resource Utilization</span>
                    <svg class="w-5 h-5 text-zinc-600 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-zinc-50">View Report</h3>
                    <p class="text-xs font-medium mt-1 text-zinc-500 group-hover:text-emerald-400/70 transition-colors">Analyze organizational capacity</p>
                </div>
            </a>

            <!-- Quick Actions -->
            <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6">
                <h3 class="text-base font-semibold text-zinc-50 mb-4">Quick Actions</h3>
                
                <div class="flex flex-col gap-2.5">
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-start gap-3 px-4 py-3 rounded-lg text-sm font-medium border border-zinc-800 text-zinc-300 bg-transparent hover:bg-zinc-900 hover:text-zinc-50 transition-colors">
                        <svg class="text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add New User
                    </a>
                    
                    <a href="{{ route('admin.resources.create') }}" class="inline-flex items-center justify-start gap-3 px-4 py-3 rounded-lg text-sm font-medium border border-zinc-800 text-zinc-300 bg-transparent hover:bg-zinc-900 hover:text-zinc-50 transition-colors">
                        <svg class="text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Resource
                    </a>
                    
                    <a href="{{ route('admin.maintenance.create') }}" class="inline-flex items-center justify-start gap-3 px-4 py-3 rounded-lg text-sm font-medium border border-zinc-800 text-zinc-300 bg-transparent hover:bg-zinc-900 hover:text-zinc-50 transition-colors">
                        <svg class="text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Schedule Maintenance
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('bookingTrendChart').getContext('2d');
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)'); // emerald-400
    gradient.addColorStop(1, 'rgba(52, 211, 153, 0)');

    const labels = {!! json_encode($chartLabels) !!};
    const dataValues = {!! json_encode($chartValues) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Approved Bookings',
                data: dataValues,
                borderColor: '#34d399', // emerald-400
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#18181b', // zinc-950
                pointBorderColor: '#34d399',
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181b',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    borderColor: '#27272a',
                    borderWidth: 1,
                    padding: 12
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#71717a', font: { size: 11 } }
                },
                y: {
                    grid: { color: '#27272a', drawBorder: false, borderDash: [4, 4] },
                    ticks: {
                        color: '#71717a',
                        font: { size: 11 },
                        stepSize: 1,
                        beginAtZero: true
                    }
                }
            }
        }
    });
});
</script>
@endsection