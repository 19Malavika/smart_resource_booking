@extends('layouts.app')
@section('title', 'Edit Category — ' . $category->name)
@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Categories</a>
        <h1 class="text-2xl font-bold text-zinc-50">Edit Category</h1>
        <p class="text-sm text-zinc-400 mt-1">{{ $category->name }}</p>
    </div>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
            @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Approval Type <span class="text-red-400">*</span></label>
            <select name="approval_type" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500 max-w-xs">
                <option value="NONE" {{ old('approval_type', $category->approval_type) == 'NONE' ? 'selected' : '' }}>None (Auto-approve)</option>
                <option value="MANAGER" {{ old('approval_type', $category->approval_type) == 'MANAGER' ? 'selected' : '' }}>Manager Approval</option>
                <option value="ADMIN" {{ old('approval_type', $category->approval_type) == 'ADMIN' ? 'selected' : '' }}>Admin Approval</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">{{ old('description', $category->description) }}</textarea>
        </div>
        <div class="flex items-center gap-3 pt-2 border-t border-zinc-800">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
