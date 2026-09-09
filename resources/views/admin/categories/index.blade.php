@extends('layouts.app')
@section('title', 'Resource Categories')
@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div><h1 class="text-2xl font-bold text-zinc-50 mb-1">Resource Categories</h1><p class="text-sm text-zinc-400">Manage resource classification</p></div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Add Category</a>
    </div>
    @if(session('success'))<div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">{{ session('error') }}</div>@endif
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b border-zinc-800 bg-zinc-900/30"><th class="px-5 py-3 font-medium text-zinc-400">Name</th><th class="px-5 py-3 font-medium text-zinc-400">Approval Type</th><th class="px-5 py-3 font-medium text-zinc-400">Resources</th><th class="px-5 py-3 font-medium text-zinc-400">Description</th><th class="px-5 py-3 font-medium text-zinc-400 text-right">Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3 font-medium text-zinc-200">{{ $cat->name }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-mono text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded">{{ $cat->approval_type }}</span></td>
                        <td class="px-5 py-3 text-zinc-300">{{ $cat->resources_count }}</td>
                        <td class="px-5 py-3 text-zinc-400 truncate max-w-[200px]">{{ $cat->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')" class="inline">@csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-zinc-500 py-10">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
