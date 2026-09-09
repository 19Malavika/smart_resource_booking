@extends('layouts.app')
@section('title', 'Edit Resource — ' . $resource->name)
@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.resources.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>Back to Resources</a>
        <h1 class="text-2xl font-bold text-zinc-50">Edit Resource</h1>
        <p class="text-sm text-zinc-400 mt-1">{{ $resource->name }} ({{ $resource->code }})</p>
    </div>
    <form method="POST" action="{{ route('admin.resources.update', $resource) }}" enctype="multipart/form-data" class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-5">
        @csrf @method('PUT')
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Name <span class="text-red-400">*</span></label><input type="text" name="name" value="{{ old('name', $resource->name) }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">@error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Code <span class="text-red-400">*</span></label><input type="text" name="code" value="{{ old('code', $resource->code) }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">@error('code')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror</div>
        </div>
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Category <span class="text-red-400">*</span></label><select name="category_id" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $resource->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Manager</label><select name="manager_id" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="">— None —</option>@foreach($managers as $mgr)<option value="{{ $mgr->id }}" {{ old('manager_id', $resource->manager_id) == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>@endforeach</select></div>
        </div>
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Location</label><input type="text" name="location" value="{{ old('location', $resource->location) }}" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"></div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Capacity</label><input type="number" name="capacity" value="{{ old('capacity', $resource->capacity) }}" min="1" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"></div>
        </div>
        <div class="reveal-up grid grid-cols-1 md:grid-cols-3 gap-5">
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Status <span class="text-red-400">*</span></label><select name="status" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="AVAILABLE" {{ old('status', $resource->status) == 'AVAILABLE' ? 'selected' : '' }}>Available</option><option value="UNAVAILABLE" {{ old('status', $resource->status) == 'UNAVAILABLE' ? 'selected' : '' }}>Unavailable</option><option value="MAINTENANCE" {{ old('status', $resource->status) == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option></select></div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Approval Type <span class="text-red-400">*</span></label><select name="approval_type" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"><option value="NONE" {{ old('approval_type', $resource->approval_type) == 'NONE' ? 'selected' : '' }}>None</option><option value="MANAGER" {{ old('approval_type', $resource->approval_type) == 'MANAGER' ? 'selected' : '' }}>Manager</option><option value="ADMIN" {{ old('approval_type', $resource->approval_type) == 'ADMIN' ? 'selected' : '' }}>Admin</option></select></div>
            <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Price</label><input type="number" name="price" value="{{ old('price', $resource->price) }}" step="0.01" min="0" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"></div>
        </div>
        <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Description</label><textarea name="description" rows="3" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">{{ old('description', $resource->description) }}</textarea></div>
        <div><label class="block text-sm font-medium text-zinc-300 mb-1.5">Image</label><input type="file" name="image" accept="image/*" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-zinc-700 file:text-zinc-200"></div>
        <div class="flex items-center gap-3 pt-2 border-t border-zinc-800">
            <button type="submit" class="btn btn-primary">Update Resource</button>
            <a href="{{ route('admin.resources.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
