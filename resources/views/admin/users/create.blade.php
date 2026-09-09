@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="reveal-up max-w-3xl mx-auto">
    
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Users
        </a>
        <h1 class="text-2xl font-bold text-zinc-50">Add New User</h1>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 space-y-5">
        @csrf
        
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Email <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                <input type="password" name="password" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Confirm Password <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
        </div>

        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Employee ID</label>
                <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
                @error('employee_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Role <span class="text-red-400">*</span></label>
                <select name="role" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Department</label>
                <select name="department_id" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">— None —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Designation</label>
                <input type="text" name="designation" value="{{ old('designation') }}" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-zinc-300 mb-1.5">Manager</label>
                <select name="manager_id" class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">— None —</option>
                    @foreach($managers as $mgr)
                        <option value="{{ $mgr->id }}" {{ old('manager_id') == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1.5">Status <span class="text-red-400">*</span></label>
            <select name="status" required class="w-full px-3 py-2.5 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500 max-w-xs">
                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-zinc-800">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
