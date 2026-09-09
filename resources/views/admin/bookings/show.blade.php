@extends('layouts.app')
@section('title', 'Booking Details')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Bookings</a>
        <h1 class="text-2xl font-bold text-zinc-50">Booking #{{ $booking->id }}</h1>
    </div>
    
    @if(session('success'))<div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>@endif

    <!-- Booking Details -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <h3 class="text-base font-semibold text-zinc-50">Booking Details</h3>
            @php
                $sClasses = match(strtolower($booking->status)) {
                    'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    'rejected', 'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                    default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                };
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border uppercase {{ $sClasses }}">{{ $booking->status }}</span>
        </div>
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Resource</h4><p class="text-sm text-zinc-200">{{ $booking->resource->name }}</p><p class="text-xs text-zinc-500">{{ $booking->resource->category->name ?? '' }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Employee</h4><p class="text-sm text-zinc-200">{{ $booking->user->name }}</p><p class="text-xs text-zinc-500">{{ $booking->user->email }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Start</h4><p class="text-sm text-zinc-200">{{ $booking->start_datetime->format('l, M j, Y — H:i') }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">End</h4><p class="text-sm text-zinc-200">{{ $booking->end_datetime->format('l, M j, Y — H:i') }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Purpose</h4><p class="text-sm text-zinc-300">{{ $booking->purpose }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Priority</h4>
                @php $pClass = match($booking->priority) { 'URGENT' => 'text-red-400', 'HIGH' => 'text-amber-400', default => 'text-zinc-300' }; @endphp
                <span class="text-sm font-medium {{ $pClass }}">{{ $booking->priority }}</span>
            </div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Participants</h4><p class="text-sm text-zinc-200">{{ $booking->participants }}</p></div>
            <div><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Submitted</h4><p class="text-sm text-zinc-200">{{ $booking->created_at->format('M j, Y H:i') }}</p></div>
        </div>
        @if($booking->notes)
        <div class="mt-4 pt-4 border-t border-zinc-800"><h4 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Notes</h4><p class="text-sm text-zinc-300">{{ $booking->notes }}</p></div>
        @endif
        @if($booking->rejection_reason)
        <div class="mt-4 pt-4 border-t border-zinc-800"><h4 class="text-xs font-medium text-red-400/80 uppercase tracking-wider mb-1">Rejection Reason</h4><p class="text-sm text-red-300">{{ $booking->rejection_reason }}</p></div>
        @endif
        @if($booking->cancellation_reason)
        <div class="mt-4 pt-4 border-t border-zinc-800"><h4 class="text-xs font-medium text-red-400/80 uppercase tracking-wider mb-1">Cancellation Reason</h4><p class="text-sm text-red-300">{{ $booking->cancellation_reason }}</p></div>
        @endif
    </div>

    <!-- Action Forms -->
    @if($booking->status === 'PENDING')
    <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Approve -->
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="bg-zinc-950 border border-emerald-500/20 rounded-xl p-5">
            @csrf @method('PUT')
            <input type="hidden" name="action" value="approve">
            <h4 class="text-sm font-semibold text-emerald-400 mb-3">Approve Booking</h4>
            <p class="text-xs text-zinc-400 mb-4">This will approve the booking and notify the employee.</p>
            <button type="submit" class="w-full btn btn-success">✓ Approve</button>
        </form>
        <!-- Reject -->
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="bg-zinc-950 border border-red-500/20 rounded-xl p-5">
            @csrf @method('PUT')
            <input type="hidden" name="action" value="reject">
            <h4 class="text-sm font-semibold text-red-400 mb-3">Reject Booking</h4>
            <textarea name="rejection_reason" placeholder="Reason for rejection..." required rows="2" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-red-500/50 mb-3"></textarea>
            @error('rejection_reason') <p class="text-red-400 text-xs mb-2">{{ $message }}</p> @enderror
            <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-sm font-medium bg-red-600 text-white hover:bg-red-500 transition-colors">✕ Reject</button>
        </form>
    </div>
    @endif

    @if(in_array($booking->status, ['PENDING', 'APPROVED']))
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-5">
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
            @csrf @method('PUT')
            <input type="hidden" name="action" value="cancel">
            <h4 class="text-sm font-semibold text-zinc-300 mb-3">Cancel Booking</h4>
            <textarea name="cancellation_reason" placeholder="Reason for cancellation (optional)..." rows="2" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500 mb-3"></textarea>
            <button type="submit" onclick="return confirm('Are you sure you want to cancel this booking?')" class="px-4 py-2.5 rounded-lg text-sm font-medium border border-red-500/30 text-red-400 bg-transparent hover:bg-red-500/10 transition-colors">Cancel Booking</button>
        </form>
    </div>
    @endif
</div>
@endsection
