@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
        <div>
            <div style="font-size: 14px; color: #565959; margin-bottom: 8px;">
                <a href="{{ route('bookings.index') }}" style="color: #007185; text-decoration: none;">My Bookings</a>
                <span style="margin: 0 8px;">›</span>
                <span style="color: #0f1111; font-weight: 700;">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <h1 style="font-size: 28px; font-weight: 700; color: #0f1111;">{{ $booking->purpose }}</h1>
        </div>
        
        <div style="display: flex; gap: 12px;">
            @if(in_array($booking->status, ['PENDING', 'APPROVED']) && $booking->start_datetime > now())
                @can('update', $booking)
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-outline" style="border-radius: 8px;">
                    Edit Details
                </a>
                @endcan
                
                @can('cancel', $booking)
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="color: #c40000; border-color: #c40000; border-radius: 8px;">
                        Cancel Booking
                    </button>
                </form>
                @endcan
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="card" style="border-left: 4px solid #067d62; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" fill="none" stroke="#067d62" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div style="color: #0f1111; font-size: 14px; font-weight: 700;">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="card" style="padding: 0;">
        <div style="border-bottom: 1px solid var(--border-light); padding: 24px; display: flex; justify-content: space-between; align-items: center; background-color: #f8f8f8; border-radius: 8px 8px 0 0;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #0f1111;">Booking Summary</h3>
                <p style="font-size: 14px; color: #565959; margin-top: 4px;">Details of your reservation.</p>
            </div>
            <div>
                <span class="badge badge-{{ strtolower($booking->status) }}" style="font-size: 14px; padding: 6px 12px;">
                    {{ $booking->status }}
                </span>
            </div>
        </div>
        
        <div style="padding: 0;">
            <!-- Resource -->
            <div style="display: flex; padding: 16px 24px; border-bottom: 1px solid var(--border-light);">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Resource</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111; font-weight: 700;">
                    {{ $booking->resource->name }} 
                    <span style="color: #565959; font-weight: 400;">({{ $booking->resource->category?->name }})</span>
                </div>
            </div>
            
            <!-- Date & Time -->
            <div style="display: flex; padding: 16px 24px; border-bottom: 1px solid var(--border-light);">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Date & Time</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111;">
                    <div style="font-weight: 700;">{{ $booking->start_datetime->format('l, F j, Y') }}</div>
                    <div style="color: #565959; margin-top: 4px;">{{ $booking->start_datetime->format('h:i A') }} - {{ $booking->end_datetime->format('h:i A') }}</div>
                </div>
            </div>
            
            <!-- Participants -->
            <div style="display: flex; padding: 16px 24px; border-bottom: 1px solid var(--border-light);">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Participants</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111;">
                    {{ $booking->participants }} people
                </div>
            </div>
            
            <!-- Priority -->
            <div style="display: flex; padding: 16px 24px; border-bottom: 1px solid var(--border-light);">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Priority</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111;">
                    {{ $booking->priority }}
                </div>
            </div>
            
            <!-- Booked By -->
            <div style="display: flex; padding: 16px 24px; {{ $booking->notes ? 'border-bottom: 1px solid var(--border-light);' : '' }}">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Booked By</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111;">
                    {{ $booking->user->name }}
                </div>
            </div>
            
            <!-- Notes -->
            @if($booking->notes)
            <div style="display: flex; padding: 16px 24px;">
                <div style="flex: 0 0 150px; font-size: 14px; font-weight: 700; color: #565959;">Notes</div>
                <div style="flex: 1; font-size: 14px; color: #0f1111; white-space: pre-line;">
                    {{ $booking->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
