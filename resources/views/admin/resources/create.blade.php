@extends('layouts.app')

@section('title', 'Add New Resource')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 24px; font-weight: 700; color: #0f1111; margin-bottom: 8px;">Add New Resource</h1>
        <div style="font-size: 14px; color: #565959;">
            <a href="{{ route('resources.index') }}" style="color: #007185; text-decoration: none;">Resources</a>
            <span style="margin: 0 8px;">›</span>
            <span style="color: #0f1111; font-weight: 700;">Create</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="card" style="border-left: 4px solid #c40000; padding: 16px; margin-bottom: 24px;">
            <h3 style="color: #c40000; font-weight: 700; font-size: 14px; margin-bottom: 8px;">There was a problem</h3>
            <ul style="color: #0f1111; font-size: 13px; padding-left: 20px; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="padding: 0;">
        <form action="{{ route('resources.store') }}" method="POST">
            @csrf

            <div style="padding: 24px; display: flex; gap: 24px; flex-wrap: wrap;">
                <!-- Left Column -->
                <div style="flex: 1; min-width: 300px;">
                    <div style="margin-bottom: 16px;">
                        <label for="name" class="form-label">Resource Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="code" class="form-label">Unique Code (e.g., MR-001)</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" class="form-control" required>
                        @error('code')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control" style="background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;" required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="manager_id" class="form-label">Resource Manager (Optional)</label>
                        <select name="manager_id" id="manager_id" class="form-control" style="background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;">
                            <option value="">None</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->role }})</option>
                            @endforeach
                        </select>
                        @error('manager_id')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div style="flex: 1; min-width: 300px;">
                    <div style="margin-bottom: 16px;">
                        <label for="location" class="form-label">Location (Optional)</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control">
                        @error('location')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="capacity" class="form-label">Capacity (Number of Participants)</label>
                        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', 1) }}" class="form-control" style="width: 150px;" required>
                        @error('capacity')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" style="background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;" required>
                            <option value="AVAILABLE" {{ old('status') == 'AVAILABLE' ? 'selected' : '' }}>Available</option>
                            <option value="UNAVAILABLE" {{ old('status') == 'UNAVAILABLE' ? 'selected' : '' }}>Unavailable</option>
                            <option value="MAINTENANCE" {{ old('status') == 'MAINTENANCE' ? 'selected' : '' }}>Maintenance</option>
                            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="approval_type" class="form-label">Approval Requirement</label>
                        <select name="approval_type" id="approval_type" class="form-control" style="background-color: #f0f2f2; border-color: #d5d9d9; box-shadow: 0 1px 2px rgba(15,17,17,.15) inset;" required>
                            <option value="NONE" {{ old('approval_type') == 'NONE' ? 'selected' : '' }}>None (Automatic)</option>
                            <option value="MANAGER" {{ old('approval_type') == 'MANAGER' ? 'selected' : '' }}>Manager Approval</option>
                            <option value="ADMIN" {{ old('approval_type') == 'ADMIN' ? 'selected' : '' }}>Admin Approval</option>
                        </select>
                        @error('approval_type')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div style="flex: 0 0 100%;">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                    @error('description')<p style="color: #c40000; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="padding: 16px 24px; display: flex; justify-content: flex-end; gap: 12px; background-color: #f0f2f2; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
                <a href="{{ route('resources.index') }}" class="btn btn-outline" style="border-radius: 8px;">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Resource</button>
            </div>
        </form>
    </div>
</div>
@endsection
