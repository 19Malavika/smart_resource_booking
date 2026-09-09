@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <div class="text-sm text-zinc-400 mb-2">
                <a href="{{ route('bookings.index') }}" class="text-zinc-50 hover:text-zinc-300 no-underline transition-colors">My Bookings</a>
                <span class="mx-2">›</span>
                <span class="text-zinc-400 font-medium">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 class="text-2xl font-bold text-zinc-50">{{ $booking->purpose }}</h1>
        </div>
        
        <div class="flex gap-3">
            @if($booking->status === 'PENDING')
                @can('approve', $booking)
                <form action="{{ route('bookings.approve', $booking) }}" method="POST" class="m-0" onsubmit="return confirm('Approve Booking?\n\nEmployee: {{ addslashes($booking->user->name) }}\nResource: {{ addslashes($booking->resource->name) }}\nDate: {{ $booking->start_datetime->format('d M Y') }}\nTime: {{ $booking->start_datetime->format('h:i A') }} – {{ $booking->end_datetime->format('h:i A') }}\n\nAre you sure you want to approve this booking?');">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Approve
                    </button>
                </form>
                @endcan

                @can('reject', $booking)
                <form action="{{ route('bookings.reject', $booking) }}" method="POST" class="m-0" onsubmit="const reason = prompt('Reject Booking\n\nEmployee: {{ addslashes($booking->user->name) }}\nResource: {{ addslashes($booking->resource->name) }}\nDate: {{ $booking->start_datetime->format('d M Y') }}\nTime: {{ $booking->start_datetime->format('h:i A') }} – {{ $booking->end_datetime->format('h:i A') }}\n\nReason for rejection:'); if(!reason) return false; this.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'rejection_reason\' value=\'' + reason.replace(/'/g, '&apos;') + '\'>'); return true;">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-bold border border-red-500/50 text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reject
                    </button>
                </form>
                @endcan
                
                @if(auth()->user()->cannot('reject', $booking))
                    @can('update', $booking)
                    <a href="{{ route('bookings.edit', $booking) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-zinc-800 text-zinc-50 hover:bg-zinc-900 transition-colors">
                        Edit Details
                    </a>
                    @endcan
                    
                    @can('cancel', $booking)
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="m-0" onsubmit="const reason = prompt('Are you sure you want to cancel this booking? Please provide a cancellation reason:'); if(!reason) return false; this.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'cancellation_reason\' value=\'' + reason.replace(/'/g, '&apos;') + '\'>'); return true;">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-red-500/50 text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                            Cancel Request
                        </button>
                    </form>
                    @endcan
                @endif
                
            @elseif($booking->status === 'APPROVED' && $booking->start_datetime > now())
                @can('update', $booking)
                <a href="{{ route('bookings.edit', $booking) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-zinc-800 text-zinc-50 hover:bg-zinc-900 transition-colors">
                    Edit Details
                </a>
                @endcan
                
                @can('cancel', $booking)
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="m-0" onsubmit="const reason = prompt('Are you sure you want to cancel this booking? Please provide a cancellation reason:'); if(!reason) return false; this.insertAdjacentHTML('beforeend', '<input type=\'hidden\' name=\'cancellation_reason\' value=\'' + reason.replace(/'/g, '&apos;') + '\'>'); return true;">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-red-500/50 text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                        Cancel Booking
                    </button>
                </form>
                @endcan
            @endif

            @if(in_array($booking->status, ['REJECTED', 'CANCELLED', 'COMPLETED', 'EXPIRED']) || $booking->start_datetime <= now())
                <a href="{{ route('bookings.create', ['category_id' => $booking->resource->category_id, 'resource_id' => $booking->resource_id]) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-zinc-700 bg-zinc-800 text-zinc-50 hover:bg-zinc-700 hover:border-zinc-600 transition-colors">
                    Book Again
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-zinc-950 border-l-4 border-l-emerald-400 border border-zinc-800 rounded-lg p-4 mb-6 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div class="text-sm font-semibold text-zinc-50">{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-zinc-950 rounded-xl border border-zinc-800 shadow-sm overflow-hidden">
        @php
            $placeholders = [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1556761175-5973dc0f32b7?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80',
            ];
            $fallbackImage = $placeholders[$booking->resource->id % count($placeholders)];
            $image = $booking->resource->image_path ? asset($booking->resource->image_path) : $fallbackImage;
            
            $statusClasses = match(strtolower($booking->status)) {
                'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-[0_0_12px_rgba(16,185,129,0.2)]',
                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20 shadow-[0_0_12px_rgba(245,158,11,0.2)]',
                'rejected', 'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_12px_rgba(239,68,68,0.2)]',
                default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
            };
        @endphp

        <!-- Image Banner -->
        <div class="relative h-48 w-full bg-zinc-900 border-b border-zinc-800 overflow-hidden">
            <img src="{{ $image }}" alt="{{ $booking->resource->name }}" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6 flex justify-between items-end">
                <div>
                    <h3 class="text-xl font-bold text-zinc-50">{{ $booking->resource->name }}</h3>
                    <p class="text-[13px] text-zinc-300 mt-1 font-medium">{{ $booking->resource->category?->name ?? 'Resource' }}</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border backdrop-blur-md capitalize {{ $statusClasses }}">
                        {{ strtolower($booking->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-0">
            <!-- Resource -->
            <div class="flex p-4 px-6 border-b border-zinc-800">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Resource</div>
                <div class="flex-1 text-sm font-medium text-zinc-50">
                    {{ $booking->resource->name }} 
                    <span class="text-zinc-400 font-normal">({{ $booking->resource->category?->name }})</span>
                </div>
            </div>
            
            <!-- Date & Time -->
            <div class="flex p-4 px-6 border-b border-zinc-800">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Date & Time</div>
                <div class="flex-1 text-sm text-zinc-50">
                    <div class="font-medium">{{ $booking->start_datetime->format('l, F j, Y') }}</div>
                    <div class="text-zinc-400 mt-1">{{ $booking->start_datetime->format('h:i A') }} - {{ $booking->end_datetime->format('h:i A') }}</div>
                </div>
            </div>
            
            <!-- Participants -->
            <div class="flex p-4 px-6 border-b border-zinc-800">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Participants</div>
                <div class="flex-1 text-sm text-zinc-50">
                    {{ $booking->participants }} people
                </div>
            </div>
            
            <!-- Priority -->
            <div class="flex p-4 px-6 border-b border-zinc-800">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Priority</div>
                <div class="flex-1 text-sm text-zinc-50">
                    {{ $booking->priority }}
                </div>
            </div>
            
            <!-- Booked By -->
            <div class="flex p-4 px-6 {{ $booking->notes ? 'border-b border-zinc-800' : '' }}">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Booked By</div>
                <div class="flex-1 text-sm text-zinc-50">
                    {{ $booking->user->name }}
                </div>
            </div>
            
            <!-- Notes -->
            @if($booking->notes)
            <div class="flex p-4 px-6 {{ $booking->rejection_reason || $booking->cancellation_reason ? 'border-b border-zinc-800' : '' }}">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-zinc-400">Notes</div>
                <div class="flex-1 text-sm text-zinc-50 whitespace-pre-line">
                    {{ $booking->notes }}
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($booking->rejection_reason)
            <div class="flex p-4 px-6 bg-red-500/5">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-red-400">Rejection Reason</div>
                <div class="flex-1 text-sm text-red-300 font-medium whitespace-pre-line">
                    {{ $booking->rejection_reason }}
                </div>
            </div>
            @endif

            <!-- Cancellation Reason -->
            @if($booking->cancellation_reason)
            <div class="flex p-4 px-6 bg-red-500/5">
                <div class="flex-none w-[150px] text-[13px] font-semibold text-red-400">Cancellation Reason</div>
                <div class="flex-1 text-sm text-red-300 font-medium whitespace-pre-line">
                    {{ $booking->cancellation_reason }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection