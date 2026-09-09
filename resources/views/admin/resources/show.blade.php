@extends('layouts.app')
@section('title', 'Resource — ' . $resource->name)
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <a href="{{ route('resources.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Resources</a>
            <h1 class="text-2xl font-bold text-zinc-50">{{ $resource->name }}</h1>
        </div>
        @can('update', $resource)
        <a href="{{ route('resources.edit', $resource) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Edit</a>
        @endcan
    </div>
    
    @php
        $placeholders = [
            'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1556761175-5973dc0f32b7?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80',
        ];
        $fallbackImage = $placeholders[$resource->id % count($placeholders)];
        $image = $resource->image_path ? asset($resource->image_path) : $fallbackImage;
        
        $statusClasses = match(strtolower($resource->status)) {
            'available' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-[0_0_12px_rgba(16,185,129,0.2)]',
            'maintenance' => 'bg-amber-500/10 text-amber-400 border-amber-500/20 shadow-[0_0_12px_rgba(245,158,11,0.2)]',
            'unavailable' => 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_12px_rgba(239,68,68,0.2)]',
            default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
        };
    @endphp

    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden mb-6 shadow-sm">
        <!-- Image Banner -->
        <div class="relative h-48 w-full bg-zinc-900 border-b border-zinc-800 overflow-hidden">
            <img src="{{ $image }}" alt="{{ $resource->name }}" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6 flex justify-between items-end">
                <div>
                    <h3 class="text-xl font-bold text-zinc-50">{{ $resource->name }}</h3>
                    <p class="text-[13px] text-zinc-300 mt-1 font-medium">{{ $resource->category?->name ?? 'Resource' }}</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border backdrop-blur-md capitalize {{ $statusClasses }}">
                        {{ strtolower($resource->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
        <div class="reveal-up grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Code</h3><p class="text-sm text-zinc-200 font-mono">{{ $resource->code }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Category</h3><p class="text-sm text-zinc-200">{{ $resource->category->name ?? '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Manager</h3><p class="text-sm text-zinc-200">{{ $resource->manager->name ?? '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Location</h3><p class="text-sm text-zinc-200">{{ $resource->location ?? '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Capacity</h3><p class="text-sm text-zinc-200">{{ $resource->capacity ?? '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Status</h3>
                @php $sClass = match($resource->status) { 'AVAILABLE' => 'text-emerald-400', 'MAINTENANCE' => 'text-amber-400', default => 'text-red-400' }; @endphp
                <span class="text-sm font-medium {{ $sClass }}">{{ $resource->status }}</span>
            </div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Approval</h3><p class="text-sm text-zinc-200">{{ $resource->approval_type }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Price</h3><p class="text-sm text-zinc-200">{{ $resource->price ? '$' . number_format($resource->price, 2) : 'Free' }}</p></div>
        </div>
        @if($resource->description)
        <div class="mt-4 pt-4 border-t border-zinc-800"><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Description</h3><p class="text-sm text-zinc-300">{{ $resource->description }}</p></div>
        @endif
        </div>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
        <h3 class="text-base font-semibold text-zinc-50 mb-4">Recent Bookings</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b border-zinc-800"><th class="py-2.5 font-medium text-zinc-400">User</th><th class="py-2.5 font-medium text-zinc-400">Date & Time</th><th class="py-2.5 font-medium text-zinc-400">Status</th></tr></thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30">
                        <td class="py-2.5 text-zinc-200">{{ $booking->user->name }}</td>
                        <td class="py-2.5 text-zinc-300">{{ $booking->start_datetime->format('M j, Y H:i') }} - {{ $booking->end_datetime->format('H:i') }}</td>
                        <td class="py-2.5">
                            @php $sClasses = match(strtolower($booking->status)) { 'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', default => 'bg-zinc-800 text-zinc-300 border-zinc-700' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $sClasses }}">{{ strtolower($booking->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-zinc-500 py-6">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
