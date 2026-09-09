@extends('layouts.app')
@section('title', 'Add Department')
@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.departments.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Departments</a>
        <h1 class="text-2xl font-bold text-zinc-50">Add Department</h1>
    </div>
    <form method="POST" action="{{ route('admin.departments.store') }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-5">
        @csrf
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Code <span class="text-red-400">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                @error('code') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">{{ old('description') }}</textarea>
        </div>
        <div class="flex items-center gap-3 pt-2 border-t border-zinc-800">
            <button type="submit" class="btn btn-primary">Create Department</button>
            <a href="{{ route('admin.departments.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
