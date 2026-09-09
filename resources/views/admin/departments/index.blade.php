@extends('layouts.app')
@section('title', 'Department Management')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1">Departments</h1>
            <p class="text-sm text-zinc-400">Manage organizational departments</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-success gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Department
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>
    @endif
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.departments.index') }}" class="flex gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-zinc-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or code..." class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors">Filter</button>
            <a href="{{ route('admin.departments.index') }}" class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200">Clear</a>
        </form>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30">
                        <th class="px-5 py-3 font-medium text-zinc-400">Name</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Code</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Employees</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Description</th>
                        <th class="px-5 py-3 font-medium text-zinc-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3 font-medium text-zinc-200">{{ $dept->name }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-mono text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded">{{ $dept->code }}</span></td>
                        <td class="px-5 py-3 text-zinc-300">{{ $dept->employees_count }}</td>
                        <td class="px-5 py-3 text-zinc-400 truncate max-w-[200px]">{{ $dept->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.departments.show', $dept) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                <a href="{{ route('admin.departments.edit', $dept) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" onsubmit="return confirm('Delete this department?')" class="inline">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-zinc-500 py-10">No departments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $departments->links() }}</div>
</div>
@endsection
