@extends('layouts.app')
@section('title', 'Schedule Maintenance')
@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.maintenance.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Maintenance</a>
        <h1 class="text-2xl font-bold text-zinc-50">Schedule Maintenance</h1>
    </div>
    <form method="POST" action="{{ route('admin.maintenance.store') }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-5">
        @csrf
        <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Resource <span class="text-red-400">*</span></label><select name="resource_id" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="">Select resource...</option>@foreach($resources as $res)<option value="{{ $res->id }}" {{ old('resource_id') == $res->id ? 'selected' : '' }}>{{ $res->name }} ({{ $res->code }})</option>@endforeach</select>@error('resource_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Start Date & Time <span class="text-red-400">*</span></label><input type="datetime-local" name="start_datetime" value="{{ old('start_datetime') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">@error('start_datetime')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">End Date & Time <span class="text-red-400">*</span></label><input type="datetime-local" name="end_datetime" value="{{ old('end_datetime') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">@error('end_datetime')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
        </div>
        <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Reason <span class="text-red-400">*</span></label><textarea name="reason" rows="3" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">{{ old('reason') }}</textarea>@error('reason')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
        <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Status <span class="text-red-400">*</span></label><select name="status" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500 max-w-xs"><option value="SCHEDULED" {{ old('status') == 'SCHEDULED' ? 'selected' : '' }}>Scheduled</option><option value="IN_PROGRESS" {{ old('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option><option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option></select></div>
        <div class="flex items-center gap-3 pt-2 border-t border-zinc-800">
            <button type="submit" class="btn btn-primary">Schedule Maintenance</button>
            <a href="{{ route('admin.maintenance.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
