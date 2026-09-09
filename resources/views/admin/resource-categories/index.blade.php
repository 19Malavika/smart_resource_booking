@extends('layouts.app')

@section('title', 'Resource Categories')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1 tracking-tight">Resource Categories</h1>
            <p class="text-sm text-zinc-400 mt-1">Manage the types of resources available for booking.</p>
        </div>
        <a href="{{ route('resource-categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>

    @if(session('success'))
        <div class="card border-l-4 border-l-emerald-500 mb-6 bg-emerald-500/10 p-4">
            <div class="flex items-center gap-3">
                <svg width="24" height="24" fill="none" stroke="currentColor" class="text-emerald-400" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div class="text-zinc-50 text-[13px] font-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Approval Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="font-bold text-zinc-50">{{ $category->name }}</td>
                            <td class="text-zinc-400">{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-md text-[11px] font-medium bg-zinc-800 text-zinc-300">
                                    {{ $category->approval_type }}
                                </span>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('resource-categories.edit', $category) }}" class="btn btn-outline px-2 h-7 text-xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('resource-categories.destroy', $category) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-2 h-7 text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-zinc-500 py-12 text-[13px]">
                                No resource categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
        <div class="p-4 border-t border-zinc-800">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
