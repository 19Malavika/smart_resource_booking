@extends('layouts.app')

@section('title', 'Book a Resource')

@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1 tracking-tight">Book a Resource</h1>
            <div class="text-[13px] text-zinc-500 font-medium flex items-center gap-2">
                <a href="{{ route('bookings.index') }}" class="text-zinc-400 hover:text-zinc-50 transition-colors no-underline">My Bookings</a>
                <span>&rsaquo;</span>
                <span class="text-zinc-50">New Booking</span>
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

    <div class="card p-0 overflow-hidden" x-data="{ 
        selectedResource: '{{ old('resource_id', request('resource_id', '')) }}',
        resources: {{ $resources->mapWithKeys(function($r) { return [$r->id => ['name' => $r->name, 'image' => $r->image_path ? asset($r->image_path) : null]]; })->toJson() }}
    }">
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <div class="p-6 border-b border-zinc-800">
                <h3 class="text-lg font-semibold text-zinc-50 mb-4">1. Select Resource</h3>
                
                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex gap-4 items-start">
                        <!-- Resource Thumbnail Preview -->
                        <div class="h-20 w-28 shrink-0 rounded-lg bg-zinc-800 border border-zinc-700 overflow-hidden flex items-center justify-center text-zinc-500 mt-6" x-show="selectedResource">
                            <template x-if="resources[selectedResource] && resources[selectedResource].image">
                                <img :src="resources[selectedResource].image" :alt="resources[selectedResource].name" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!(resources[selectedResource] && resources[selectedResource].image)">
                                <svg class="w-6 h-6 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </template>
                        </div>

                        <div class="flex-1">
                            <label for="resource_id" class="form-label">Available Resources</label>
                            <select name="resource_id" id="resource_id" class="form-control" required x-model="selectedResource">
                                <option value="">Select a resource to book</option>
                                @foreach($categories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach($resources->where('category_id', $category->id) as $resource)
                                            <option value="{{ $resource->id }}">
                                                {{ $resource->name }} (Capacity: {{ $resource->capacity }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="participants" class="form-label">Number of Participants</label>
                        <input type="number" name="participants" id="participants" min="1" value="{{ old('participants', 1) }}" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="p-6 border-b border-zinc-800">
                <h3 class="text-lg font-semibold text-zinc-50 mb-4">2. Schedule & Details</h3>
                
                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="start_datetime" class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" value="{{ old('start_datetime') }}" class="form-control" required>
                    </div>
                    <div>
                        <label for="end_datetime" class="form-label">End Time <span class="text-zinc-500 font-normal text-xs">(on the same day)</span></label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" value="{{ old('end_datetime') }}" class="form-control" required>
                    </div>
                </div>

                <!-- Recurring Booking Toggle -->
                <div class="mb-6 border border-zinc-800 rounded-lg p-4 bg-zinc-900/30" x-data="{ isRecurring: {{ old('is_recurring') ? 'true' : 'false' }} }">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1" class="w-4 h-4 text-emerald-500 bg-zinc-900 border-zinc-700 rounded focus:ring-emerald-500 focus:ring-2" x-model="isRecurring">
                        <label for="is_recurring" class="ml-2 text-sm font-medium text-zinc-300 cursor-pointer">Make this a recurring booking</label>
                    </div>
                    
                    <div x-show="isRecurring" x-collapse class="mt-4 pt-4 border-t border-zinc-800/50">
                        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="recurring_pattern" class="form-label">Repeat Pattern</label>
                                <select name="recurring_pattern" id="recurring_pattern" class="form-control" :required="isRecurring">
                                    <option value="DAILY" {{ old('recurring_pattern') == 'DAILY' ? 'selected' : '' }}>Daily</option>
                                    <option value="WEEKDAYS" {{ old('recurring_pattern') == 'WEEKDAYS' ? 'selected' : '' }}>Every Weekday (Mon-Fri)</option>
                                    <option value="WEEKLY" {{ old('recurring_pattern', 'WEEKLY') == 'WEEKLY' ? 'selected' : '' }}>Weekly (Same day of week)</option>
                                </select>
                            </div>
                            <div>
                                <label for="recurring_occurrences" class="form-label">Total Occurrences</label>
                                <input type="number" name="recurring_occurrences" id="recurring_occurrences" min="2" max="52" value="{{ old('recurring_occurrences', 2) }}" class="form-control" placeholder="e.g. 8" :required="isRecurring">
                                <p class="text-xs text-zinc-500 mt-1">Maximum 52 occurrences (e.g. 1 year of weekly bookings)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="purpose" class="form-label">Purpose</label>
                        <input type="text" name="purpose" id="purpose" value="{{ old('purpose') }}" placeholder="e.g. Quarterly Team Sync" class="form-control" required>
                    </div>

                    <div>
                        <label for="priority" class="form-label">Priority Level</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="LOW" {{ old('priority') == 'LOW' ? 'selected' : '' }}>Low</option>
                            <option value="NORMAL" {{ old('priority', 'NORMAL') == 'NORMAL' ? 'selected' : '' }}>Normal</option>
                            <option value="HIGH" {{ old('priority') == 'HIGH' ? 'selected' : '' }}>High</option>
                            <option value="URGENT" {{ old('priority') == 'URGENT' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-zinc-900/50">
                <label for="notes" class="form-label">Additional Notes <span class="text-zinc-500 font-normal">(Optional)</span></label>
                <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Any special requirements?">{{ old('notes') }}</textarea>
            </div>

            <div class="p-5 flex justify-end gap-3 bg-zinc-900 border-t border-zinc-800">
                <a href="{{ route('bookings.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Place your booking request</button>
            </div>
        </form>
    </div>
</div>
@endsection
