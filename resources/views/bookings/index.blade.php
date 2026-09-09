@extends('layouts.app')

@section('title', 'Resource Bookings')

@section('content')
@php $fallbackImage = 'https://via.placeholder.com/600x400'; @endphp
<div class="reveal-up max-w-7xl mx-auto space-y-6">
    
    <!-- Header & Action Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 tracking-tight">Resource Bookings</h1>
            <p class="text-sm text-zinc-400">Explore, track, and manage all resource reservations.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filter Pills -->
            <!-- Calendar & Create Button -->

            <!-- Calendar & Create Button -->
            <a href="{{ route('bookings.calendar') }}" class="btn btn-outline h-9 px-3.5 text-xs text-zinc-300">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Calendar
            </a>
            @can('create', App\Models\Booking::class)
            <a href="{{ route('bookings.create') }}" class="btn btn-primary h-9 px-4 text-xs font-semibold">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Booking
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="bg-zinc-950 border-l-4 border-l-emerald-400 border border-zinc-800 rounded-lg p-4 shadow-sm flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div class="text-sm font-semibold text-zinc-50">{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm p-4">
        <form method="GET" action="{{ route('bookings.index') }}" class="flex flex-wrap items-end gap-4">
            @if(isset($users) && $users->isNotEmpty())
            <div class="flex-1 min-w-[150px]">
                <label for="user_id" class="block text-[13px] font-medium text-zinc-50 mb-1.5">Employee</label>
                <select name="user_id" id="user_id" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="flex-1 min-w-[150px]">
                <label for="category_id" class="block text-[13px] font-medium text-zinc-50 mb-1.5">Category</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1 min-w-[150px]">
                <label for="resource_id" class="block text-[13px] font-medium text-zinc-50 mb-1.5">Resource</label>
                <select name="resource_id" id="resource_id" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
                    <option value="">All Resources</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ request('resource_id') == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label for="status" class="block text-[13px] font-medium text-zinc-50 mb-1.5">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
                    <option value="">All Statuses</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label for="priority" class="block text-[13px] font-medium text-zinc-50 mb-1.5">Priority</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
                    <option value="">All Priorities</option>
                    <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>Low</option>
                    <option value="NORMAL" {{ request('priority') == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                    <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>High</option>
                    <option value="URGENT" {{ request('priority') == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            
            <div class="flex-1 min-w-[150px]">
                <label for="start_date" class="block text-[13px] font-medium text-zinc-50 mb-1.5">From Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
            </div>

            <div class="flex-1 min-w-[150px]">
                <label for="end_date" class="block text-[13px] font-medium text-zinc-50 mb-1.5">To Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded-lg text-[13px] text-zinc-50 focus:border-zinc-700 focus:ring-2 focus:ring-zinc-800/50 outline-none transition-all">
            </div>

            <div>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-[13px] font-medium border border-zinc-800 text-zinc-50 bg-transparent hover:bg-zinc-900 transition-colors">Filter</button>
            </div>
        </form>
    </div>

    <!-- Creative Tim Style 3D Cards Grid -->
    <div class="reveal-up grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($bookings as $booking)
            @php
                $statusUpper = strtoupper($booking->status);
                $statusBadge = match($statusUpper) {
                    'APPROVED' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30 shadow-[0_0_12px_rgba(16,185,129,0.2)]',
                    'PENDING' => 'bg-amber-500/15 text-amber-400 border-amber-500/30 shadow-[0_0_12px_rgba(245,158,11,0.2)]',
                    'REJECTED', 'CANCELLED' => 'bg-red-500/15 text-red-400 border-red-500/30 shadow-[0_0_12px_rgba(239,68,68,0.2)]',
                    default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                };

                $priorityDot = match(strtolower($booking->priority ?? 'normal')) {
                    'high', 'urgent', 'critical' => 'bg-rose-500 ring-rose-500/30',
                    'medium', 'normal' => 'bg-blue-400 ring-blue-400/30',
                    default => 'bg-zinc-400 ring-zinc-400/30'
                };
                $categoryName = strtolower($booking->resource->category->name ?? '');
                
                if (str_contains($categoryName, 'vehicle') || str_contains($categoryName, 'car') || str_contains($categoryName, 'transport')) {
                    $placeholders = [
                        'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1503376760367-12ea2c99db49?auto=format&fit=crop&w=600&q=80',
                    ];
                } elseif (str_contains($categoryName, 'room') || str_contains($categoryName, 'space') || str_contains($categoryName, 'hall')) {
                    $placeholders = [
                        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1556761175-5973dc0f32b7?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80',
                    ];
                } else {
                    $placeholders = [
                        'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1505409859467-3a796fd5798e?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1531973576160-7125cd663d86?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1524758870432-af57e54afa26?auto=format&fit=crop&w=600&q=80',
                        'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=600&q=80',
                    ];
                }
                
                // Assign a category-specific placeholder based on booking ID for variety
                $image = $placeholders[$booking->id % count($placeholders)];
            @endphp

            <div class="card group flex flex-col h-full rounded-2xl border border-zinc-800/80 hover:border-zinc-700">
                <!-- Image Container with Aspect Ratio and Overlay -->
                <div class="relative h-48 w-full overflow-hidden bg-zinc-900">
                    <img src="{{ $image }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';" alt="{{ $booking->resource->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
                    
                    <!-- Top Bar Badges -->
                    <div class="absolute top-3 left-3 right-3 flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-zinc-950/80 backdrop-blur-md text-zinc-200 border border-zinc-700/60 shadow-sm">
                            {{ $booking->resource->category->name ?? 'Resource' }}
                        </span>
                        
                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold border backdrop-blur-md {{ $statusBadge }}">
                            {{ $statusUpper }}
                        </span>
                    </div>

                    <!-- Bottom Resource Title inside Image -->
                    <div class="absolute bottom-3 left-4 right-4">
                        <h3 class="text-lg font-bold text-zinc-50 drop-shadow-md truncate">{{ $booking->resource->name }}</h3>
                        <p class="text-xs text-zinc-300/90 flex items-center gap-1.5 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Reserved by <span class="font-medium text-white">{{ $booking->user->name ?? 'Self' }}</span>
                        </p>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <!-- Purpose / Reservation Details -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Purpose</span>
                            <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                                <span class="w-2 h-2 rounded-full ring-2 {{ $priorityDot }}"></span>
                                <span class="capitalize">{{ $booking->priority ?? 'Normal' }} Priority</span>
                            </div>
                        </div>
                        <p class="text-sm text-zinc-300 line-clamp-2 leading-relaxed bg-zinc-900/40 p-2.5 rounded-lg border border-zinc-800/60">
                            {{ $booking->purpose ?: 'No specific purpose detailed.' }}
                        </p>
                    </div>

                    <!-- Time & Schedule Information -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between text-xs py-1.5 px-3 rounded-md bg-zinc-900/60 border border-zinc-800/50">
                            <span class="text-zinc-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Date:
                            </span>
                            <span class="font-medium text-zinc-200">{{ $booking->start_datetime->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs py-1.5 px-3 rounded-md bg-zinc-900/60 border border-zinc-800/50">
                            <span class="text-zinc-400 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Time Slot:
                            </span>
                            <span class="font-medium text-zinc-200">{{ $booking->start_datetime->format('h:i A') }} - {{ $booking->end_datetime->format('h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="pt-3 border-t border-zinc-800/80 flex items-center justify-between gap-3">
                        <span class="text-[11px] text-zinc-500">
                            #{{ substr($booking->id, 0, 8) }}
                        </span>
                        
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline h-8 px-4 text-xs font-medium text-zinc-200 hover:text-white flex items-center gap-1.5 group-hover:border-zinc-600 transition-all">
                            <span>View Details</span>
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 px-6 text-center card border-dashed border-zinc-800">
                <div class="w-16 h-16 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center mx-auto mb-4 text-zinc-400 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-base font-semibold text-zinc-100 mb-1">No Bookings Found</h3>
                <p class="text-sm text-zinc-400 max-w-sm mx-auto mb-6">There are no reservations matching your current filter criteria.</p>
                @can('create', App\Models\Booking::class)
                <a href="{{ route('bookings.create') }}" class="btn btn-primary text-xs font-semibold px-5 py-2.5">
                    Make a Reservation
                </a>
                @endcan
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $bookings->links() }}
    </div>
    @endif

</div>
@endsection