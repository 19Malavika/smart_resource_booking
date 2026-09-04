@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f1111; margin-bottom: 8px;">Edit Booking Details</h1>
        <div style="font-size: 14px; color: #565959;">
            <a href="{{ route('bookings.index') }}" style="color: #007185; text-decoration: none;">My Bookings</a>
            <span style="margin: 0 8px;">›</span>
            <a href="{{ route('bookings.show', $booking) }}" style="color: #007185; text-decoration: none;">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</a>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #0f1111; font-weight: 700;">Edit</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="border-left: 4px solid #c40000; padding: 16px; margin-bottom: 24px;">
            <h3 style="color: #c40000; font-weight: 700; font-size: 14px; margin-bottom: 8px;">There was a problem</h3>
            <ul style="color: #0f1111; font-size: 13px; padding-left: 20px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 0;">
        <form action="{{ route('bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="padding: 24px; border-bottom: 1px solid var(--border-light); background-color: #f8f8f8; border-radius: 8px 8px 0 0;">
                <h4 style="font-size: 14px; font-weight: 700; color: #0f1111; margin-bottom: 4px;">Resource Info</h4>
                <p style="font-size: 14px; color: #0f1111;">{{ $booking->resource->name }} ({{ $booking->resource->category->name }}) - Capacity: {{ $booking->resource->capacity }}</p>
                <p style="font-size: 12px; color: #565959; margin-top: 4px;">Resource selection cannot be changed. Create a new booking to switch resources.</p>
            </div>

            <div style="padding: 24px; border-bottom: 1px solid var(--border-light);">
                <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div style="flex: 1; max-width: 250px;">
                        <label for="start_datetime" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime', $booking->start_datetime->format('Y-m-d\TH:i')) }}" class="form-control" required>
                    </div>
                    <div style="flex: 1; max-width: 250px;">
                        <label for="end_datetime" class="form-label">End Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime', $booking->end_datetime->format('Y-m-d\TH:i')) }}" class="form-control" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="participants" class="form-label">Number of Participants</label>
                    <input type="number" name="participants" id="participants" min="1" max="{{ $booking->resource->capacity }}" value="{{ old('participants', $booking->participants) }}" class="form-control" required style="max-width: 150px;">
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="purpose" class="form-label">Purpose</label>
                    <input type="text" name="purpose" id="purpose" value="{{ old('purpose', $booking->purpose) }}" class="form-control" required style="max-width: 400px;">
                </div>

                <div>
                    <label for="priority" class="form-label">Priority Level</label>
                    <select name="priority" id="priority" class="form-control" style="max-width: 200px; background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;">
                        <option value="LOW" {{ old('priority', $booking->priority) == 'LOW' ? 'selected' : '' }}>Low</option>
                        <option value="NORMAL" {{ old('priority', $booking->priority) == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                        <option value="HIGH" {{ old('priority', $booking->priority) == 'HIGH' ? 'selected' : '' }}>High</option>
                        <option value="URGENT" {{ old('priority', $booking->priority) == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div style="padding: 24px; background-color: #f8f8f8;">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $booking->notes) }}</textarea>
            </div>

            <div style="padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background-color: #f0f2f2; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline" style="border-radius: 8px;">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
