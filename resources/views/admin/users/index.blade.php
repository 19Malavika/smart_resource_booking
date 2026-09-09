@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="reveal-up max-w-7xl mx-auto">
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-50 mb-1">User Management</h1>
            <p class="text-sm text-zinc-400">Manage all system users, roles and permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-zinc-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, employee ID..." class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500">
            </div>
            <div class="w-40">
                <label class="block text-xs text-zinc-500 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                </select>
            </div>
            <div class="w-44">
                <label class="block text-xs text-zinc-500 mb-1">Department</label>
                <select name="department_id" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-36">
                <label class="block text-xs text-zinc-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200 transition-colors">Clear</a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-900/30">
                        <th class="px-5 py-3 font-medium text-zinc-400">Name</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Email</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Role</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Department</th>
                        <th class="px-5 py-3 font-medium text-zinc-400">Status</th>
                        <th class="px-5 py-3 font-medium text-zinc-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium text-zinc-200">{{ $user->name }}</div>
                            @if($user->employee_id)
                            <div class="text-xs text-zinc-500 mt-0.5">{{ $user->employee_id }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-zinc-300">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @php
                                $roleClasses = match($user->role) {
                                    'admin' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                    'manager' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    default => 'bg-zinc-800 text-zinc-300 border-zinc-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $roleClasses }}">{{ $user->role }}</span>
                        </td>
                        <td class="px-5 py-3 text-zinc-400">{{ $user->department->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Active</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-zinc-500"><span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-zinc-400 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-zinc-500 py-10">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
