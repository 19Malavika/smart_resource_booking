@extends('layouts.app')
@section('title', 'Audit Logs')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div><h1 class="text-2xl font-bold text-zinc-50 mb-1">Audit Logs</h1><p class="text-sm text-zinc-400">Track all administrative actions across the system</p></div>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]"><label class="block text-xs text-zinc-500 mb-1">Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Action or user name..." class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500"></div>
            <div class="w-48"><label class="block text-xs text-zinc-500 mb-1">Action</label><select name="action" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="">All Actions</option>@foreach($actionTypes as $action)<option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $action)) }}</option>@endforeach</select></div>
            <div class="w-40"><label class="block text-xs text-zinc-500 mb-1">From</label><input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"></div>
            <div class="w-40"><label class="block text-xs text-zinc-500 mb-1">To</label><input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"></div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors">Filter</button>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200">Clear</a>
        </form>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30">
                        <th class="px-5 py-3 font-medium text-zinc-400">Timestamp</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">User</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Action</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Target</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">IP Address</th>
                        <th class="px-5 py-3 font-medium text-zinc-400 text-right">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="text-zinc-300">{{ $log->created_at->format('M j, Y') }}</div>
                            <div class="text-xs text-zinc-500">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-5 py-3 text-zinc-200">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-800 text-zinc-300 border border-zinc-700">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span>
                        </td>
                        <td class="px-5 py-3 text-zinc-400 text-xs font-mono">
                            @if($log->auditable_type)
                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                            @else
                            —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-zinc-500 text-xs font-mono">{{ $log->ip_address ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.audit-logs.show', $log) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors inline-block"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-zinc-500 py-10">No audit logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection
