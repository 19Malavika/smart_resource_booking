@extends('layouts.app')
@section('title', 'Maintenance Management')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div><h1 class="text-2xl font-bold text-zinc-50 mb-1">Maintenance</h1><p class="text-sm text-zinc-400">Schedule and manage resource maintenance</p></div>
        <a href="{{ route('admin.maintenance.create') }}" class="btn btn-success gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Schedule Maintenance</a>
    </div>
    @if(session('success'))<div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>@endif
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.maintenance.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]"><label class="block text-xs text-zinc-500 mb-1">Search</label><input type="text" name="search" value="{{ request('search') }}" placeholder="Resource name..." class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500"></div>
            <div class="w-40"><label class="block text-xs text-zinc-500 mb-1">Status</label><select name="status" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="">All</option><option value="SCHEDULED" {{ request('status') == 'SCHEDULED' ? 'selected' : '' }}>Scheduled</option><option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option><option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option></select></div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors">Filter</button>
            <a href="{{ route('admin.maintenance.index') }}" class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200">Clear</a>
        </form>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b border-zinc-800 bg-zinc-900/30"><th class="px-5 py-3 font-medium text-zinc-400">Resource</th><th class="px-5 py-3 font-medium text-zinc-400">Period</th><th class="px-5 py-3 font-medium text-zinc-400">Reason</th><th class="px-5 py-3 font-medium text-zinc-400">Status</th><th class="px-5 py-3 font-medium text-zinc-400">Created By</th><th class="px-5 py-3 font-medium text-zinc-400 text-right">Actions</th></tr></thead>
                <tbody>
                    @forelse($maintenances as $m)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3 font-medium text-zinc-200">{{ $m->resource->name }}</td>
                        <td class="px-5 py-3"><div class="text-zinc-300">{{ $m->start_datetime->format('M j, Y H:i') }}</div><div class="text-xs text-zinc-500">to {{ $m->end_datetime->format('M j, Y H:i') }}</div></td>
                        <td class="px-5 py-3 text-zinc-400 truncate max-w-[200px]">{{ $m->reason }}</td>
                        <td class="px-5 py-3">
                            @php $mClass = match($m->status) { 'SCHEDULED' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'IN_PROGRESS' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'COMPLETED' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', default => 'bg-zinc-800 text-zinc-300 border-zinc-700' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $mClass }}">{{ str_replace('_', ' ', $m->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-zinc-400">{{ $m->creator->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.maintenance.edit', $m) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form method="POST" action="{{ route('admin.maintenance.destroy', $m) }}" onsubmit="return confirm('Delete this record?')" class="inline">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-zinc-500 py-10">No maintenance records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $maintenances->links() }}</div>
</div>
@endsection
