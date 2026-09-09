@extends('layouts.app')

@section('title', 'Resource Utilization')

@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    
    <!-- Header & Filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1">Resource Utilization</h1>
            <p class="text-sm text-zinc-400">Monitor resource usage, booking frequency, and utilization trends.</p>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <!-- Filter Dropdowns -->
            <form action="{{ route('utilization.index') }}" method="GET" class="flex flex-wrap items-center gap-3" id="filterForm">
                
                <select name="range" class="reveal-up bg-zinc-900 border border-zinc-800 text-zinc-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition-colors" onchange="document.getElementById('filterForm').submit()">
                    <option value="today" {{ $range == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ $range == 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ $range == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="this_year" {{ $range == 'this_year' ? 'selected' : '' }}>This Year</option>
                </select>

                <select name="category_id" class="reveal-up bg-zinc-900 border border-zinc-800 text-zinc-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition-colors" onchange="document.getElementById('filterForm').submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <!-- Additional filters can be added here, e.g., Location, Status -->
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="reveal-up grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Overall Utilization -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 relative overflow-hidden group hover:border-zinc-700 transition-colors">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <h3 class="text-sm font-semibold text-zinc-400">Overall Utilization</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50 relative z-10">{{ $overallUtilization }}%</div>
            
            <!-- Progress Bar inside card -->
            <div class="w-full bg-zinc-900 rounded-full h-1.5 mt-4 relative z-10">
                <div class="h-1.5 rounded-full {{ $overallUtilization >= 80 ? 'bg-red-500' : ($overallUtilization >= 40 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $overallUtilization }}%"></div>
            </div>
        </div>

        <!-- Total Resources -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 hover:border-zinc-700 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Total Resources</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50">{{ $resources->count() }}</div>
        </div>

        <!-- Total Booked Hours -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 hover:border-zinc-700 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Total Booked Hours</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50">{{ round($totalBookedSeconds / 3600, 1) }} <span class="text-sm text-zinc-500 font-normal">hrs</span></div>
            <div class="text-xs text-zinc-500 mt-2">{{ $totalBookings }} total bookings</div>
        </div>

        <!-- Most Utilized -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 hover:border-zinc-700 transition-colors">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Most Utilized</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            @if($mostUtilized && $mostUtilized['stats']['utilization_percentage'] > 0)
                <div class="text-lg font-bold text-zinc-50 truncate" title="{{ $mostUtilized['resource']->name }}">{{ $mostUtilized['resource']->name }}</div>
                <div class="text-sm text-emerald-400 font-medium mt-1">{{ $mostUtilized['stats']['utilization_percentage'] }}% Utilization</div>
            @else
                <div class="text-lg font-bold text-zinc-500">N/A</div>
            @endif
        </div>
    </div>

    <!-- Utilization Chart -->
    <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm mb-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
            <h3 class="text-base font-semibold text-zinc-50">Resource Utilization Trend</h3>
            <div class="text-xs text-zinc-400">{{ $start->format('M j, Y') }} - {{ $end->format('M j, Y') }}</div>
        </div>
        <div class="p-6 h-[400px]">
            <canvas id="utilizationTrendChart"></canvas>
        </div>
    </div>

    <!-- Utilization Table -->
    <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-zinc-800">
            <h3 class="text-base font-semibold text-zinc-50">Detailed Breakdown</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-900/50">
                    <tr class="border-b border-zinc-800">
                        <th class="py-4 px-6 font-medium text-zinc-400">Resource</th>
                        <th class="py-4 px-6 font-medium text-zinc-400">Category</th>
                        <th class="py-4 px-6 font-medium text-zinc-400">Available Hrs</th>
                        <th class="py-4 px-6 font-medium text-zinc-400">Booked Hrs</th>
                        <th class="py-4 px-6 font-medium text-zinc-400">Bookings</th>
                        <th class="py-4 px-6 font-medium text-zinc-400">Utilization</th>
                        <th class="py-4 px-6 font-medium text-zinc-400 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($resources as $resource)
                    @php
                        $stats = $resource->utilization_stats;
                        $util = $stats['utilization_percentage'];
                        $badgeClass = $util >= 80 ? 'bg-red-500/10 text-red-400 border-red-500/20' : 
                                      ($util >= 40 ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 
                                      'bg-emerald-500/10 text-emerald-400 border-emerald-500/20');
                    @endphp
                    <tr class="hover:bg-zinc-900/50 transition-colors">
                        <td class="py-4 px-6 font-medium text-zinc-50">{{ $resource->name }}</td>
                        <td class="py-4 px-6 text-zinc-300">{{ $resource->category->name }}</td>
                        <td class="py-4 px-6 text-zinc-300">{{ $stats['total_available_hours'] }}</td>
                        <td class="py-4 px-6 text-zinc-300">{{ $stats['total_booked_hours'] }}</td>
                        <td class="py-4 px-6 text-zinc-300">{{ $stats['total_bookings'] }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                {{ $util }}%
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('utilization.show', $resource) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-zinc-700 text-xs font-medium rounded-lg text-zinc-50 hover:bg-zinc-800 transition-colors shadow-sm">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-zinc-500 py-10">No resources found matching the criteria.</td>
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
    const ctx = document.getElementById('utilizationTrendChart').getContext('2d');
    
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)'); // emerald-400
    gradient.addColorStop(1, 'rgba(52, 211, 153, 0)');

    const labels = {!! json_encode($chartLabels) !!};
    const dataValues = {!! json_encode($chartValues) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Utilization %',
                data: dataValues,
                backgroundColor: '#34d399', // emerald-400
                borderRadius: 4,
                barPercentage: 0.6
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
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '% Utilization';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#71717a' }
                },
                y: {
                    grid: { color: '#27272a', drawBorder: false },
                    ticks: {
                        color: '#71717a',
                        callback: function(value) { return value + '%'; }
                    },
                    min: 0,
                    max: 100
                }
            }
        }
    });
});
</script>
@endsection
