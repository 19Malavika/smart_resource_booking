@extends('layouts.app')

@section('title', 'Resources')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #0f1111;">Resources</h1>
            <p style="font-size: 14px; color: #565959; margin-top: 4px;">Manage physical resources available for booking.</p>
        </div>
        <a href="{{ route('resources.create') }}" class="btn btn-primary">Add Resource</a>
    </div>

    @if(session('success'))
        <div class="card" style="border-left: 4px solid #067d62; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" fill="none" stroke="#067d62" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div style="color: #0f1111; font-size: 14px; font-weight: 700;">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="card" style="padding: 16px; margin-bottom: 24px; background-color: #f8f8f8;">
        <form method="GET" action="{{ route('resources.index') }}" style="display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-control" style="background-color: #fff;">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" value="{{ request('location') }}" class="form-control" placeholder="e.g. Floor 2" style="background-color: #fff;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" style="background-color: #fff;">
                    <option value="">All Statuses</option>
                    <option value="AVAILABLE" {{ request('status') == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                    <option value="UNAVAILABLE" {{ request('status') == 'UNAVAILABLE' ? 'selected' : '' }}>Unavailable</option>
                    <option value="MAINTENANCE" {{ request('status') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-outline" style="background-color: #fff;">Filter</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Resource Code</th>
                    <th>Name & Category</th>
                    <th>Location & Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resources as $resource)
                    <tr>
                        <td style="font-weight: 700; color: #0f1111;">{{ $resource->code }}</td>
                        <td>
                            <div style="font-weight: 700; color: #0f1111;">{{ $resource->name }}</div>
                            <div style="font-size: 12px; color: #565959; margin-top: 2px;">{{ $resource->category->name }}</div>
                        </td>
                        <td>
                            <div style="color: #0f1111;">{{ $resource->location ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #565959; margin-top: 2px;">Cap: {{ $resource->capacity }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ strtolower($resource->status) }}">
                                {{ $resource->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('resources.show', $resource) }}" style="color: #007185; text-decoration: none; font-size: 13px; font-weight: 600; margin-right: 12px;">View</a>
                            <a href="{{ route('resources.edit', $resource) }}" style="color: #007185; text-decoration: none; font-size: 13px; font-weight: 600;">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 48px 24px; text-align: center;">
                            <div style="font-size: 16px; color: #565959;">No resources found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($resources->hasPages())
    <div style="margin-top: 24px;">
        {{ $resources->links() }}
    </div>
    @endif
</div>
@endsection
