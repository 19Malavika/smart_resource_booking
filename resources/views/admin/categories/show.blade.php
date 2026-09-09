@extends('layouts.app')
@section('title', 'Category — ' . $category->name)
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Categories</a>
            <h1 class="text-2xl font-bold text-zinc-50">{{ $category->name }}</h1>
        </div>
        <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Edit</a>
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 mb-6">
        <div class="reveal-up grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Approval Type</h3><p class="text-sm text-zinc-200 font-mono">{{ $category->approval_type }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Total Resources</h3><p class="text-sm text-zinc-200">{{ $category->resources->count() }}</p></div>
            <div><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Created</h3><p class="text-sm text-zinc-200">{{ $category->created_at->format('M j, Y') }}</p></div>
        </div>
        @if($category->description)
        <div class="mt-4 pt-4 border-t border-zinc-800"><h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Description</h3><p class="text-sm text-zinc-300">{{ $category->description }}</p></div>
        @endif
    </div>
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
        <h3 class="text-base font-semibold text-zinc-50 mb-4">Resources in this Category</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="border-b border-zinc-800"><th class="py-2.5 font-medium text-zinc-400">Name</th><th class="py-2.5 font-medium text-zinc-400">Code</th><th class="py-2.5 font-medium text-zinc-400">Status</th><th class="py-2.5 font-medium text-zinc-400">Location</th></tr></thead>
                <tbody>
                    @forelse($category->resources as $res)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30">
                        <td class="py-2.5 text-zinc-200">{{ $res->name }}</td>
                        <td class="py-2.5"><span class="text-xs font-mono text-zinc-400 bg-zinc-800 px-2 py-0.5 rounded">{{ $res->code }}</span></td>
                        <td class="py-2.5">
                            @php $sClass = match($res->status) { 'AVAILABLE' => 'text-emerald-400', 'MAINTENANCE' => 'text-amber-400', default => 'text-zinc-500' }; @endphp
                            <span class="text-xs font-medium {{ $sClass }}">{{ $res->status }}</span>
                        </td>
                        <td class="py-2.5 text-zinc-400">{{ $res->location ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-zinc-500 py-6">No resources in this category.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
