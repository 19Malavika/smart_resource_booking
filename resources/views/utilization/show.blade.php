@extends('layouts.app')

@section('title', 'Resource Utilization Details')

@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    
    <!-- Breadcrumbs & Header -->
    <div class="mb-6">
        <div class="flex items-center text-sm text-zinc-500 mb-2">
            <a href="{{ route('utilization.index') }}" class="hover:text-zinc-300 transition-colors">Resource Utilization</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-zinc-300">{{ $resource->name }}</span>
        </div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-zinc-50 mb-1">{{ $resource->name }} <span class="text-zinc-500 font-normal text-lg ml-2">#{{ $resource->code }}</span></h1>
                <p class="text-sm text-zinc-400">{{ $resource->category->name }} &bull; {{ $resource->location ?? 'No location specified' }}</p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <!-- Filter Dropdowns -->
                <form action="{{ route('utilization.show', $resource) }}" method="GET" class="flex flex-wrap items-center gap-3" id="filterForm">
                    <select name="range" class="reveal-up bg-zinc-900 border border-zinc-800 text-zinc-300 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 transition-colors" onchange="document.getElementById('filterForm').submit()">
                        <option value="today" {{ $range == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ $range == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $range == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year" {{ $range == 'this_year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="reveal-up grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Utilization % -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-2 relative z-10">
                <h3 class="text-sm font-semibold text-zinc-400">Utilization</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
            @php
                $util = $stats['utilization_percentage'];
                $textClass = $util >= 80 ? 'text-red-400' : ($util >= 40 ? 'text-amber-400' : 'text-emerald-400');
            @endphp
            <div class="text-3xl font-bold {{ $textClass }} relative z-10">{{ $util }}%</div>
        </div>

        <!-- Booked Hours -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Booked Hours</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50">{{ $stats['total_booked_hours'] }} <span class="text-sm text-zinc-500 font-normal">hrs</span></div>
        </div>

        <!-- Total Bookings -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Total Bookings</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50">{{ $stats['total_bookings'] }}</div>
        </div>

        <!-- Avg Duration -->
        <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-6">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-sm font-semibold text-zinc-400">Avg Booking Duration</h3>
                <svg class="w-5 h-5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-3xl font-bold text-zinc-50">{{ $avgDurationFormatted }}</div>
        </div>
    </div>

    <div class="reveal-up grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Hours Breakdown -->
        <div class="lg:col-span-1 bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-zinc-800">
                <h3 class="text-base font-semibold text-zinc-50">Hours Breakdown</h3>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center">
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-300">Total Available (Working Hrs)</span>
                            <span class="font-medium text-zinc-50">{{ $stats['total_available_hours'] }} hrs</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-zinc-800">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-400">Booked Time</span>
                            <span class="font-medium text-emerald-400">{{ $stats['total_booked_hours'] }} hrs</span>
                        </div>
                        <div class="w-full bg-zinc-900 rounded-full h-1.5 mt-2">
                            @php $bp = $stats['total_available_hours'] > 0 ? min(100, ($stats['total_booked_hours'] / $stats['total_available_hours']) * 100) : 0; @endphp
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $bp }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-400">Maintenance Time</span>
                            <span class="font-medium text-amber-400">{{ $stats['total_maintenance_hours'] }} hrs</span>
                        </div>
                        <div class="w-full bg-zinc-900 rounded-full h-1.5 mt-2">
                            @php $mp = $stats['total_available_hours'] > 0 ? min(100, ($stats['total_maintenance_hours'] / $stats['total_available_hours']) * 100) : 0; @endphp
                            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $mp }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-400">Unused Time</span>
                            <span class="font-medium text-zinc-50">{{ $unusedHours }} hrs</span>
                        </div>
                        <div class="w-full bg-zinc-900 rounded-full h-1.5 mt-2">
                            @php $up = $stats['total_available_hours'] > 0 ? min(100, ($unusedHours / $stats['total_available_hours']) * 100) : 0; @endphp
                            <div class="bg-zinc-600 h-1.5 rounded-full" style="width: {{ $up }}%"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Details -->
        <div class="lg:col-span-2 bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-base font-semibold text-zinc-50">Resource Details</h3>
                <a href="{{ route('resources.show', $resource) }}" class="text-xs font-medium text-emerald-400 hover:text-emerald-300">View Resource</a>
            </div>
            <div class="p-6">
                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Resource Name</div>
                        <div class="font-medium text-zinc-200">{{ $resource->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Unique Code</div>
                        <div class="font-medium text-zinc-200">{{ $resource->code }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Category</div>
                        <div class="font-medium text-zinc-200">{{ $resource->category->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Capacity</div>
                        <div class="font-medium text-zinc-200">{{ $resource->capacity }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Current Status</div>
                        @php
                            $statusClasses = match(strtolower($resource->status)) {
                                'available' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'in use' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'maintenance' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold border capitalize {{ $statusClasses }}">
                            {{ $resource->status }}
                        </span>
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 mb-1">Most Frequently Used Day</div>
                        <div class="font-medium text-zinc-200">{{ $popularDay }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
</div>
@endsection
