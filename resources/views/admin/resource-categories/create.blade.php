@extends('layouts.app')

@section('title', 'Create Resource Category')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f1111; margin-bottom: 8px;">Create Resource Category</h1>
        <div style="font-size: 14px; color: #565959;">
            <a href="{{ route('resource-categories.index') }}" style="color: #007185; text-decoration: none;">Resource Categories</a>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #0f1111; font-weight: 700;">Create</span>
        </div>
    </div>

    <div class="card" style="padding: 0;">
        <form action="{{ route('resource-categories.store') }}" method="POST">
            @csrf

            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required style="max-width: 400px;">
                    @error('name')
                        <p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control" style="max-width: 600px;">{{ old('description') }}</textarea>
                    @error('description')
                        <p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 16px;">
                    <label for="approval_type" class="form-label">Approval Requirement</label>
                    <select name="approval_type" id="approval_type" class="form-control" style="max-width: 300px; background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;">
                        <option value="NONE" {{ old('approval_type') == 'NONE' ? 'selected' : '' }}>None (Automatic Approval)</option>
                        <option value="MANAGER" {{ old('approval_type') == 'MANAGER' ? 'selected' : '' }}>Manager Approval Required</option>
                        <option value="ADMIN" {{ old('approval_type') == 'ADMIN' ? 'selected' : '' }}>Admin Approval Required</option>
                    </select>
                    @error('approval_type')
                        <p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div style="padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background-color: #f0f2f2; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
                <a href="{{ route('resource-categories.index') }}" class="btn btn-outline" style="border-radius: 8px;">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
