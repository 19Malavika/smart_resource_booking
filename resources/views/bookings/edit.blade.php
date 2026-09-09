@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1 tracking-tight">Edit Booking Details</h1>
            <div class="text-[13px] text-zinc-500 font-medium flex items-center gap-2">
                <a href="{{ route('bookings.index') }}" class="text-zinc-400 hover:text-zinc-50 transition-colors no-underline">My Bookings</a>
                <span>&rsaquo;</span>
                <a href="{{ route('bookings.show', $booking) }}" class="text-zinc-400 hover:text-zinc-50 transition-colors no-underline">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</a>
                <span>&rsaquo;</span>
                <span class="text-zinc-50">Edit</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="card border-l-4 border-l-red-500 mb-6 bg-red-500/10 p-4">
            <h3 class="text-red-400 font-semibold text-[13px] mb-2">There was a problem with your submission:</h3>
            <ul class="list-disc pl-5 text-red-300 text-[13px] m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-0 overflow-hidden">
        <form action="{{ route('bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            @if($booking->status === 'APPROVED')
            <div class="p-6 pb-0">
                <div class="border-l-4 border-l-amber-500 bg-amber-500/10 p-4 rounded-r-lg">
                    <h3 class="text-amber-400 font-semibold text-[13px] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Important Notice
                    </h3>
                    <p class="text-amber-300/80 text-[13px] mt-1 m-0">You are editing an <strong>APPROVED</strong> booking. Modifying the Resource, Start Time, or End Time will require this booking to undergo the approval process again.</p>
                </div>
            </div>
            @endif

            <div class="p-6 border-b border-zinc-800 bg-zinc-900/50">
                <label for="resource_id" class="form-label text-sm font-semibold text-zinc-50 mb-1">Select Resource</label>
                <select name="resource_id" id="resource_id" class="form-control" required>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ old('resource_id', $booking->resource_id) == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }} ({{ $resource->category->name }}) - Capacity: {{ $resource->capacity }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[12px] text-zinc-500 mt-1">If you change the resource, ensure your participant count doesn't exceed its capacity.</p>
            </div>

            <div class="p-6 border-b border-zinc-800">
                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_datetime" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime', $booking->start_datetime->format('Y-m-d\TH:i')) }}" class="form-control" required>
                    </div>
                    <div>
                        <label for="end_datetime" class="form-label">End Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime', $booking->end_datetime->format('Y-m-d\TH:i')) }}" class="form-control" required>
                    </div>
                </div>

                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="participants" class="form-label">Number of Participants</label>
                        <input type="number" name="participants" id="participants" min="1" max="{{ $booking->resource->capacity }}" value="{{ old('participants', $booking->participants) }}" class="form-control" required>
                    </div>

                    <div>
                        <label for="purpose" class="form-label">Purpose</label>
                        <input type="text" name="purpose" id="purpose" value="{{ old('purpose', $booking->purpose) }}" class="form-control" required>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="priority" class="form-label">Priority Level</label>
                    <select name="priority" id="priority" class="form-control md:w-1/2">
                        <option value="LOW" {{ old('priority', $booking->priority) == 'LOW' ? 'selected' : '' }}>Low</option>
                        <option value="NORMAL" {{ old('priority', $booking->priority) == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                        <option value="HIGH" {{ old('priority', $booking->priority) == 'HIGH' ? 'selected' : '' }}>High</option>
                        <option value="URGENT" {{ old('priority', $booking->priority) == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="p-6 bg-zinc-900/50">
                <label for="notes" class="form-label">Additional Notes</label>
                <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes', $booking->notes) }}</textarea>
            </div>

            <div class="p-5 flex justify-end gap-3 bg-zinc-900 border-t border-zinc-800">
                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
