@extends('layouts.app')

@section('title', 'Your Bookings')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #0f1111;">Your Bookings</h1>
            <p style="font-size: 14px; color: #565959; margin-top: 4px;">Manage your resource reservations and requests.</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">New Booking</a>
    </div>

    @if(session('success'))
        <div class="card" style="border-left: 4px solid #067d62; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" fill="none" stroke="#067d62" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div style="color: #0f1111; font-size: 14px; font-weight: 700;">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Resource</th>
                    <th>Date & Time</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #0f1111;">{{ $booking->resource->name }}</div>
                            <div style="font-size: 12px; color: #565959; margin-top: 2px;">{{ $booking->resource->category->name }}</div>
                        </td>
                        <td>
                            <div style="color: #0f1111;">{{ $booking->start_datetime->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #565959; margin-top: 2px;">{{ $booking->start_datetime->format('h:i A') }} - {{ $booking->end_datetime->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div style="color: #0f1111;">{{ Str::limit($booking->purpose, 40) }}</div>
                            <div style="font-size: 12px; color: #565959; margin-top: 2px;">Priority: {{ $booking->priority }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($booking->status) }}">
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" style="color: #007185; text-decoration: none; font-size: 13px; font-weight: 600;">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center;">
                            <div style="font-size: 16px; color: #565959; margin-bottom: 12px;">You have no bookings yet.</div>
                            <a href="{{ route('bookings.create') }}" class="btn btn-outline">Make your first booking</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
    <div style="margin-top: 24px;">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
