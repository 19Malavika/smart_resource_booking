@extends('layouts.app')
@section('title', 'Resource Management')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div><h1 class="text-2xl font-bold text-zinc-50 mb-1">Resources</h1><p class="text-sm text-zinc-400">Manage all organizational resources</p></div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.resources.create') }}" class="btn btn-success gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Add Resource</a>
        @endif
    </div>
    @if(session('success'))<div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>@endif
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ request()->url() }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-zinc-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, code, location..." class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
            <div class="w-44">
                <label class="block text-xs text-zinc-500 mb-1">Category</label>
                <select name="category_id" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="block text-xs text-zinc-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">All</option>
                    <option value="AVAILABLE" {{ request('status') == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                    <option value="UNAVAILABLE" {{ request('status') == 'UNAVAILABLE' ? 'selected' : '' }}>Unavailable</option>
                    <option value="MAINTENANCE" {{ request('status') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors">Filter</button>
            <a href="{{ request()->url() }}" class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200">Clear</a>
        </form>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30">
                        <th class="px-5 py-3 font-medium text-zinc-400">Name</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Category</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Manager</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Location</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Status</th>
                        <th class="px-5 py-3 font-medium text-zinc-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resources as $res)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium text-zinc-200">{{ $res->name }}</div>
                            <div class="text-xs text-zinc-500 font-mono mt-0.5">{{ $res->code }}</div>
                        </td>
                        <td class="px-5 py-3 text-zinc-300">{{ $res->category->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-zinc-400">{{ $res->manager->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-zinc-400">{{ $res->location ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @php $sClass = match($res->status) { 'AVAILABLE' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'MAINTENANCE' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', default => 'bg-red-500/10 text-red-400 border-red-500/20' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $sClass }}">{{ $res->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.resources.show', $res) : route('resources.show', $res) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isResourceManager())
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.resources.edit', $res) : route('resources.edit', $res) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('admin.resources.destroy', $res) }}" onsubmit="return confirm('Delete this resource?')" class="inline">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-zinc-500 py-10">No resources found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $resources->links() }}</div>
</div>
@endsection