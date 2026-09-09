@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<div class="reveal-up max-w-7xl mx-auto space-y-6">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-50 tracking-tight">Dashboard</h1>
        <p class="text-sm text-zinc-400">Welcome to your employee dashboard</p>
    </div>

    <!-- 4 Stat Cards matching CRM screenshot style -->
    <div class="reveal-up grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Available Resources -->
        <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Available Catalog</span>
                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-zinc-50">{{ number_format($availableResourcesCount) }}</h3>
                <p class="text-xs text-zinc-500 mt-1">Ready to book</p>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Total Bookings</span>
                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-zinc-50">{{ number_format($approvedCount) }}</h3>
                <p class="text-xs text-zinc-500 mt-1">Approved history</p>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Pending Requests</span>
                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-zinc-50">{{ number_format($pendingCount) }}</h3>
                <p class="text-xs text-zinc-500 mt-1">Awaiting decision</p>
            </div>
        </div>

        <!-- Upcoming Schedules -->
        <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-xl p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-medium text-zinc-400">Upcoming</span>
                <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-zinc-50">{{ number_format($upcomingBookings->count()) }}</h3>
                <p class="text-xs text-zinc-500 mt-1">Scheduled bookings</p>
            </div>
        </div>
    </div>

    <!-- Booking History Chart (Full Width) -->
    <div class="reveal-up bg-zinc-900 border border-zinc-800 rounded-xl p-6 shadow-sm">
        <h3 class="text-base font-semibold text-zinc-50 mb-6">Your Booking Trend</h3>
        <div class="w-full h-72">
            <canvas id="historyChart"></canvas>
        </div>
    </div>

    <!-- Bottom Tables Grid -->
    <div class="reveal-up grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Recent Bookings -->
        <div class="reveal-up bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h3 class="text-base font-semibold text-zinc-50">Recent Bookings</h3>
                <a href="{{ route('bookings.index') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300">View All</a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-900">
                        <tr class="border-b border-zinc-800">
                            <th class="py-3 px-6 font-medium text-zinc-400">Resource</th>
                            <th class="py-3 px-6 font-medium text-zinc-400 text-center">Status</th>
                            <th class="py-3 px-6 font-medium text-zinc-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myBookings->take(5) as $booking)
                        <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6">
                                <div class="font-medium text-zinc-50">{{ $booking->resource->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $booking->start_datetime->format('M j, Y') }}</div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                @php
                                    $statusClasses = match(strtolower($booking->status)) {
                                        'approved', 'active' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                        'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                        'rejected', 'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                        default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $statusClasses }}">
                                    {{ strtolower($booking->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-right">
                                <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-zinc-700 text-xs font-medium rounded text-zinc-300 hover:text-white hover:bg-zinc-800 transition-colors">
                                    Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-zinc-500 py-10">
                                <p class="text-sm">No recent bookings found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Book / Available Resources -->
        <div class="reveal-up bg-zinc-900 border border-zinc-800 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                <h3 class="text-base font-semibold text-zinc-50">Quick Book Resources</h3>
                <a href="{{ route('bookings.create') }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300">Browse Catalog</a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-900">
                        <tr class="border-b border-zinc-800">
                            <th class="py-3 px-6 font-medium text-zinc-400">Resource</th>
                            <th class="py-3 px-6 font-medium text-zinc-400 text-right">Price</th>
                            <th class="py-3 px-6 font-medium text-zinc-400 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($availableResourcesList as $resource)
                        <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6">
                                <div class="font-medium text-zinc-50">{{ $resource->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $resource->category->name ?? 'Resource' }}</div>
                            </td>
                            <td class="py-3 px-6 text-right text-zinc-300">
                                ${{ number_format($resource->price ?? 0, 2) }}
                            </td>
                            <td class="py-3 px-6 text-right">
                                <a href="{{ route('bookings.create', ['resource_id' => $resource->id]) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-zinc-900 bg-emerald-400 hover:bg-emerald-500 transition-colors">
                                    Book Now
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-zinc-500 py-10">
                                <p class="text-sm">No available resources at the moment.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('historyChart').getContext('2d');
    
    // Gradient for the line chart fill
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)'); // emerald-400 with opacity
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
                pointBackgroundColor: '#34d399',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#34d399',
                fill: true,
                tension: 0.4 // Smooth curve like the screenshot
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Hide legend to match screenshot style
                },
                tooltip: {
                    backgroundColor: '#18181b', // zinc-900
                    titleColor: '#f4f4f5', // zinc-50
                    bodyColor: '#a1a1aa', // zinc-400
                    borderColor: '#27272a', // zinc-800
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' bookings';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#a1a1aa', // zinc-400
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        }
                    }
                },
                y: {
                    grid: {
                        color: '#27272a', // zinc-800
                        borderDash: [5, 5],
                        drawBorder: false
                    },
                    ticks: {
                        color: '#a1a1aa', // zinc-400
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        },
                        stepSize: 1,
                        beginAtZero: true
                    },
                    min: 0
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection
