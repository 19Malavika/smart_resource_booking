@extends('layouts.app')
@section('title', 'Maintenance Details')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <a href="{{ route('admin.maintenance.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Maintenance</a>
            <h1 class="text-2xl font-bold text-zinc-50">Maintenance #{{ $maintenance->id }}</h1>
        </div>
        <a href="{{ route('admin.maintenance.edit', $maintenance) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Edit</a>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Resource</h3><p class="text-sm text-zinc-200">{{ $maintenance->resource->name }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Status</h3>
                @php $mClass = match($maintenance->status) { 'SCHEDULED' => 'text-blue-400', 'IN_PROGRESS' => 'text-amber-400', 'COMPLETED' => 'text-emerald-400', default => 'text-zinc-400' }; @endphp
                <span class="text-sm font-medium {{ $mClass }}">{{ str_replace('_', ' ', $maintenance->status) }}</span>
            </div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Start</h3><p class="text-sm text-zinc-200">{{ $maintenance->start_datetime->format('l, M j, Y — H:i') }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">End</h3><p class="text-sm text-zinc-200">{{ $maintenance->end_datetime->format('l, M j, Y — H:i') }}</p></div>
            <div class="md:col-span-2"><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Reason</h3><p class="text-sm text-zinc-300">{{ $maintenance->reason }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Created By</h3><p class="text-sm text-zinc-200">{{ $maintenance->creator->name ?? '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Created</h3><p class="text-sm text-zinc-200">{{ $maintenance->created_at->format('M j, Y H:i') }}</p></div>
        </div>
    </div>
</div>
@endsection
