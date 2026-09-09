@extends('layouts.app')

@section('title', 'User Details — ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="mb-6 flex justify-between items-start">
        <div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-400 hover:text-zinc-200 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back to Users
            </a>
            <h1 class="text-2xl font-bold text-zinc-50">{{ $user->name }}</h1>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-zinc-700 text-zinc-300 hover:bg-zinc-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
    </div>

    <!-- User Details Card -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 mb-6">
        <div class="reveal-up grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Email</h3>
                <p class="text-sm text-zinc-200">{{ $user->email }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Employee ID</h3>
                <p class="text-sm text-zinc-200">{{ $user->employee_id ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Role</h3>
                @php
                    $roleClasses = match($user->role) {
                        'admin' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                        'manager' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        default => 'bg-zinc-800 text-zinc-300 border-zinc-700',
                    };
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $roleClasses }}">{{ $user->role }}</span>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Status</h3>
                @if($user->status === 'active')
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Active</span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-zinc-500"><span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>Inactive</span>
                @endif
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Department</h3>
                <p class="text-sm text-zinc-200">{{ $user->department->name ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Designation</h3>
                <p class="text-sm text-zinc-200">{{ $user->designation ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Phone</h3>
                <p class="text-sm text-zinc-200">{{ $user->phone ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Manager</h3>
                <p class="text-sm text-zinc-200">{{ $user->manager->name ?? '—' }}</p>
            </div>
            <div>
                <h3 class="text-xs font-medium text-zinc-500 uppercase tracking-wider mb-1">Joined</h3>
                <p class="text-sm text-zinc-200">{{ $user->created_at->format('M j, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6">
        <h3 class="text-base font-semibold text-zinc-50 mb-4">Recent Bookings</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-zinc-800">
                        <th class="py-2.5 font-medium text-zinc-400">Resource</th>
                        <th class="py-2.5 font-medium text-zinc-400">Date & Time</th>
                        <th class="py-2.5 font-medium text-zinc-400">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">
                        <td class="py-2.5 text-zinc-200">{{ $booking->resource->name }}</td>
                        <td class="py-2.5 text-zinc-300">{{ $booking->start_datetime->format('M j, Y H:i') }}</td>
                        <td class="py-2.5">
                            @php
                                $sClasses = match(strtolower($booking->status)) {
                                    'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'rejected', 'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                    default => 'bg-zinc-800 text-zinc-300 border-zinc-700'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border capitalize {{ $sClasses }}">{{ strtolower($booking->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-zinc-500 py-6">No bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
