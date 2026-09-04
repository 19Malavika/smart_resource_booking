@extends('layouts.app')

@section('title', 'Book a Resource')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f1111; margin-bottom: 8px;">Book a Resource</h1>
        <div style="font-size: 14px; color: #565959;">
            <a href="{{ route('bookings.index') }}" style="color: #007185; text-decoration: none;">My Bookings</a>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #0f1111; font-weight: 700;">New Booking</span>
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
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <div style="padding: 24px; border-bottom: 1px solid var(--border-light);">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">1. Select Resource</h3>
                
                <div style="margin-bottom: 16px;">
                    <label for="resource_id" class="form-label">Available Resources</label>
                    <select name="resource_id" id="resource_id" class="form-control" required style="max-width: 400px; background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;">
                        <option value="">Select a resource to book</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($resources->where('category_id', $category->id) as $resource)
                                    <option value="{{ $resource->id }}" {{ old('resource_id', request('resource_id')) == $resource->id ? 'selected' : '' }}>
                                        {{ $resource->name }} (Capacity: {{ $resource->capacity }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="participants" class="form-label">Number of Participants</label>
                    <input type="number" name="participants" id="participants" min="1" value="{{ old('participants', 1) }}" class="form-control" required style="max-width: 150px;">
                </div>
            </div>

            <div style="padding: 24px; border-bottom: 1px solid var(--border-light);">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">2. Schedule & Details</h3>
                
                <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                    <div style="flex: 1; max-width: 250px;">
                        <label for="start_datetime" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime') }}" class="form-control" required>
                    </div>
                    <div style="flex: 1; max-width: 250px;">
                        <label for="end_datetime" class="form-label">End Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime') }}" class="form-control" required>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="purpose" class="form-label">Purpose</label>
                    <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}" placeholder="e.g. Quarterly Team Sync" class="form-control" required style="max-width: 400px;">
                </div>

                <div>
                    <label for="priority" class="form-label">Priority Level</label>
                    <select name="priority" id="priority" class="form-control" style="max-width: 200px; background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;">
                        <option value="LOW" {{ old('priority') == 'LOW' ? 'selected' : '' }}>Low</option>
                        <option value="NORMAL" {{ old('priority', 'NORMAL') == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                        <option value="HIGH" {{ old('priority') == 'HIGH' ? 'selected' : '' }}>High</option>
                        <option value="URGENT" {{ old('priority') == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div style="padding: 24px; background-color: #f8f8f8;">
                <label for="notes" class="form-label">Additional Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Any special requirements?">{{ old('notes') }}</textarea>
            </div>

            <div style="padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background-color: #f0f2f2; border-top: 1px solid var(--border-light);">
                <a href="{{ route('bookings.index') }}" class="btn btn-outline" style="border-radius: 8px;">Cancel</a>
                <button type="submit" class="btn btn-primary">Place your booking request</button>
            </div>
        </form>
    </div>
</div>
@endsection
