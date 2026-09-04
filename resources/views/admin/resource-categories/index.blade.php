@extends('layouts.app')

@section('title', 'Resource Categories')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #0f1111;">Resource Categories</h1>
            <p style="font-size: 14px; color: #565959; margin-top: 4px;">Manage the types of resources available for booking.</p>
        </div>
        <a href="{{ route('resource-categories.create') }}" class="btn btn-primary">Add Category</a>
    </div>

    @if(session('success'))
        <div class="card" style="border-left: 4px solid #067d62; padding: 16px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" fill="none" stroke="#067d62" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div style="color: #0f1111; font-size: 14px; font-weight: 700;">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="table-container">
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
                        <td style="font-weight: 700; color: #0f1111;">{{ $category->name }}</td>
                        <td style="color: #565959;">{{ Str::limit($category->description, 50) }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($category->approval_type) }}">
                                {{ $category->approval_type }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('resource-categories.edit', $category) }}" style="color: #007185; text-decoration: none; font-size: 13px; font-weight: 600; margin-right: 12px;">Edit</a>
                            <form action="{{ route('resource-categories.destroy', $category) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #c40000; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 48px 24px; text-align: center;">
                            <div style="font-size: 16px; color: #565959;">No resource categories found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($categories->hasPages())
    <div style="margin-top: 24px;">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
