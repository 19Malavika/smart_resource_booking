@extends('layouts.app')
@section('title', 'Audit Log Details')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Audit Logs</a>
        <h1 class="text-2xl font-bold text-zinc-50">Audit Log #{{ $auditLog->id }}</h1>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-6">
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Timestamp</h3><p class="text-sm text-zinc-200">{{ $auditLog->created_at->format('l, M j, Y — H:i:s') }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">User</h3><p class="text-sm text-zinc-200">{{ $auditLog->user->name ?? 'System' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Action</h3><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-800 text-zinc-300 border border-zinc-700">{{ ucwords(str_replace('_', ' ', $auditLog->action)) }}</span></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Target</h3><p class="text-sm text-zinc-200 font-mono">{{ $auditLog->auditable_type ? class_basename($auditLog->auditable_type) . ' #' . $auditLog->auditable_id : '—' }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">IP Address</h3><p class="text-sm text-zinc-200 font-mono">{{ $auditLog->ip_address ?? '—' }}</p></div>
        </div>

        @if($auditLog->old_values)
        <div class="pt-4 border-t border-zinc-800">
            <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-2">Old Values</h3>
            <div class="reveal-up bg-zinc-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs text-zinc-300 font-mono">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif

        @if($auditLog->new_values)
        <div class="pt-4 border-t border-zinc-800">
            <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-2">New Values</h3>
            <div class="reveal-up bg-zinc-900 rounded-lg p-4 overflow-x-auto">
                <pre class="text-xs text-emerald-300 font-mono">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
