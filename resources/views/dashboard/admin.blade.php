@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">Admin Dashboard Overview</h1>
    <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">Real-time system stats, resource metrics, and booking counts.</p>
</div>

<!-- Stats Grid 1 -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalEmployees }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Employees</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalManagers }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Resource Managers</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalCategories }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Categories</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalResources }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Resources</div>
        </div>
    </div>
</div>

<!-- Stats Grid 2 -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $totalBookings }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Total Bookings</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $pendingBookings }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Pending Requests</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $approvedBookings }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Approved Bookings</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 20px; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div>
            <div style="font-size: 24px; font-weight: 700; color: var(--text-main); line-height: 1.2;">{{ $rejectedBookings }}</div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 500;">Rejected Bookings</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
    <!-- Recent Bookings -->
    <div class="card" style="padding: 0;">
        <div class="card-header" style="padding: 24px 24px 16px 24px; margin-bottom: 0; border-bottom: none;">
            <h2 class="card-title" style="margin-bottom: 0;">Recent Bookings</h2>
            <a href="{{ route('bookings.index') }}" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px;">View All</a>
        </div>
        <div class="table-container" style="border: none; border-top: 1px solid var(--border-light); border-radius: 0 0 8px 8px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Resource</th>
                        <th>Dates</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--text-main);">{{ $booking->user->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $booking->user->employee_id }}</div>
                        </td>
                        <td style="font-weight: 500;">{{ $booking->resource->name }}</td>
                        <td>{{ $booking->start_datetime->format('d M') }} - {{ $booking->end_datetime->format('d M, Y') }}</td>
                        <td><span class="badge badge-{{ strtolower($booking->priority) }}">{{ ucfirst($booking->priority) }}</span></td>
                        <td><span class="badge badge-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px 0; font-size: 13px;">No bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
