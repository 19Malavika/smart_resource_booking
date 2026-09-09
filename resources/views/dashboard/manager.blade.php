@extends('layouts.app')

@section('title', 'Resource Manager Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-zinc-50">Resource Manager Dashboard</h1>
    <p class="text-sm text-zinc-400 mt-1">Welcome to your management dashboard</p>
</div>

<!-- Stats Overview -->
<div class="reveal-up grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Resources -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-zinc-700 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-400">Total Resources</span>
            <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($assignedResources) }}</h3>
            <p class="text-xs font-medium mt-1"><span class="text-emerald-400">+12%</span> <span class="text-zinc-500">from last month</span></p>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-zinc-700 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-400">Pending Requests</span>
            <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($pendingBookings->count()) }}</h3>
            <p class="text-xs font-medium mt-1"><span class="text-emerald-400">+8%</span> <span class="text-zinc-500">from last month</span></p>
        </div>
    </div>

    <!-- Approved Bookings -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-zinc-700 transition-colors">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-400">Active Approvals</span>
            <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-zinc-50">{{ number_format($approvedBookings->count()) }}</h3>
            <p class="text-xs font-medium mt-1"><span class="text-emerald-400">+23%</span> <span class="text-zinc-500">from last month</span></p>
        </div>
    </div>
    
    <!-- Resource Utilization -->
    <a href="{{ route('utilization.index') }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between hover:border-emerald-500/50 hover:bg-zinc-900/50 transition-all group block">
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium text-zinc-400 group-hover:text-zinc-300">Resource Utilization</span>
            <svg class="w-5 h-5 text-zinc-500 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <h3 class="text-3xl font-bold text-zinc-50">View Report</h3>
            <p class="text-xs font-medium mt-1"><span class="text-emerald-400">Track usage trends</span></p>
        </div>
    </a>
</div>

<!-- Chart -->
<div class="bg-zinc-950 border border-zinc-800 rounded-xl shadow-sm mb-8 overflow-hidden">
    <div class="px-6 py-5 border-b border-zinc-800">
        <h3 class="text-base font-semibold text-zinc-50">Booking Trend</h3>
    </div>
    <div class="p-6 h-[400px]">
        <canvas id="utilizationChart"></canvas>
    </div>
</div>

<!-- Bottom Lists -->
<div class="reveal-up grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Recent Activity -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
        <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
            <h3 class="text-base font-semibold text-zinc-50">Recent Activity</h3>
            <a href="{{ route('bookings.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition-colors">Manage Approvals</a>
        </div>
        
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-900">
                    <tr class="border-b border-zinc-800">
                        <th class="py-3 px-6 font-medium text-zinc-400">Requested By</th>
                        <th class="py-3 px-6 font-medium text-zinc-400">Resource</th>
                        <th class="py-3 px-6 font-medium text-zinc-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($pendingBookings->take(5) as $booking)
                    <tr class="hover:bg-zinc-900/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-semibold text-zinc-50">{{ $booking->user->name }}</div>
                            <div class="text-xs text-zinc-500 mt-0.5">{{ $booking->start_datetime->format('M j') }} - {{ $booking->end_datetime->format('M j') }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-zinc-200">{{ $booking->resource->name }}</div>
                            @php
                                $priorityClasses = match(strtolower($booking->priority)) {
                                    'urgent', 'high' => 'bg-red-500/10 text-red-400 border border-red-500/20',
                                    default => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 mt-1 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider {{ $priorityClasses }}">
                                {{ $booking->priority }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-zinc-700 text-xs font-medium rounded-lg text-zinc-50 hover:bg-zinc-800 transition-colors shadow-sm">
                                Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-zinc-500 py-10">
                            <p class="text-sm">No recent activity.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upcoming Tasks -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
        <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
            <h3 class="text-base font-semibold text-zinc-50">Upcoming Tasks</h3>
            <a href="{{ route('bookings.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300 transition-colors">View All</a>
        </div>
        
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-900">
                    <tr class="border-b border-zinc-800">
                        <th class="py-3 px-6 font-medium text-zinc-400">Task / Resource</th>
                        <th class="py-3 px-6 font-medium text-zinc-400">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($upcomingBookings->take(5) as $booking)
                    <tr class="hover:bg-zinc-900/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-semibold text-zinc-50">{{ $booking->resource->name }}</div>
                            <div class="text-xs text-zinc-500 mt-0.5">For {{ $booking->user->name }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-zinc-200">{{ $booking->start_datetime->format('M j, Y') }}</div>
                            <div class="text-xs text-zinc-500 mt-0.5">{{ $booking->start_datetime->format('h:i A') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-zinc-500 py-10">
                            <p class="text-sm">No upcoming tasks scheduled.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('utilizationChart').getContext('2d');
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)'); // emerald-400
    gradient.addColorStop(1, 'rgba(52, 211, 153, 0)');

    const labels = {!! json_encode($chartLabels) !!};
    const dataValues = {!! json_encode($chartValues) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bookings',
                data: dataValues,
                borderColor: '#34d399', // emerald-400
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#18181b', // zinc-900
                pointBorderColor: '#34d399',
                pointHoverBackgroundColor: '#34d399',
                pointHoverBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#18181b',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    borderColor: '#27272a',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#71717a',
                    }
                },
                y: {
                    grid: {
                        color: '#27272a',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#71717a',
                        stepSize: 1,
                        beginAtZero: true,
                    },
                    min: 0,
                    suggestedMax: Math.max(...dataValues, 5)
                }
            }
        }
    });
});
</script>
@endsection
