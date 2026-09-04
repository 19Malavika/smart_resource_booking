@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<style>
    .profile-header {
        display: flex;
        align-items: flex-start;
        gap: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 32px;
    }
    
    .profile-avatar-lg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #f1f5f9;
        color: var(--primary);
        font-size: 36px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--border-light);
        flex-shrink: 0;
    }

    .profile-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .profile-subtitle {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 13px;
        color: var(--text-muted);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .meta-item svg {
        width: 16px;
        height: 16px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-dot.active { background: #15803d; }
    .status-dot.inactive { background: #b91c1c; }

    .role-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .role-employee { background: #f1f5f9; color: #475569; }
    .role-resource_manager { background: #eff6ff; color: #1d4ed8; }
    .role-admin { background: #fef2f2; color: #b91c1c; }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-light);
        padding-bottom: 8px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .info-value {
        font-size: 14px;
        color: var(--text-main);
        font-weight: 500;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: #f8fafc;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        padding: 16px;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
    }
    
    .stat-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-muted);
    }
</style>

<div style="margin-bottom: 24px;">
    <a href="javascript:history.back()" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px;">← Back</a>
</div>

<div class="card">
    <div class="profile-header">
        <div class="profile-avatar-lg">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h1 class="profile-title">
                        {{ $user->name }}
                        <span class="role-badge role-{{ $user->role }}">{{ str_replace('_', ' ', $user->role) }}</span>
                    </h1>
                    <div class="profile-subtitle">{{ $user->designation ?? 'N/A' }}</div>
                </div>
                @if(auth()->user()->isAdmin() || auth()->id() === $user->id)
                <a href="{{ auth()->id() === $user->id ? route('profile.edit') : route('admin.employees.edit', $user) }}" class="btn btn-outline" style="font-size: 13px;">Edit Profile</a>
                @endif
            </div>

            <div class="profile-meta">
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <span style="font-family: monospace; font-size: 13px; font-weight: 600;">{{ $user->employee_id }}</span>
                </div>
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    {{ $user->department?->name ?? 'No Department' }}
                </div>
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ $user->email }}
                </div>
                @if($user->phone)
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    {{ $user->phone }}
                </div>
                @endif
                <div class="meta-item">
                    <span class="status-dot {{ $user->status === 'active' ? 'active' : 'inactive' }}"></span>
                    {{ ucfirst($user->status ?? 'active') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Personal & Employment Information (Common) -->
    <h2 class="section-title">Personal & Employment Information</h2>
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Full Name</span>
            <span class="info-value">{{ $user->name }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Joining Date</span>
            <span class="info-value">{{ $user->joining_date ? $user->joining_date->format('M d, Y') : 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Reporting Manager</span>
            <span class="info-value">
                @if($user->manager)
                    <a href="{{ route('users.show', $user->manager) }}" style="color: var(--accent); text-decoration: none;">{{ $user->manager->name }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">Email Address</span>
            <span class="info-value">{{ $user->email }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Phone Number</span>
            <span class="info-value">{{ $user->phone ?? 'N/A' }}</span>
        </div>
    </div>

    @if($user->isEmployee() || $user->isAdmin() || $user->isResourceManager())
        <!-- General specific (everyone can book) -->
        <h2 class="section-title">Booking Information</h2>
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_bookings'] ?? 0 }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['approved_bookings'] ?? 0 }}</div>
                <div class="stat-label">Approved Bookings</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['pending_bookings'] ?? 0 }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['rejected_bookings'] ?? 0 }}</div>
                <div class="stat-label">Rejected Bookings</div>
            </div>
        </div>

        <h2 class="section-title">Recent Booking Requests</h2>
        <div class="table-container" style="margin-bottom: 32px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->bookings as $booking)
                    <tr>
                        <td style="font-weight: 500;">{{ $booking->resource->name }}</td>
                        <td>{{ $booking->start_datetime->format('M d, Y H:i') }}</td>
                        <td>{{ $booking->end_datetime->format('M d, Y H:i') }}</td>
                        <td>{{ ucfirst($booking->priority) }}</td>
                        <td><span class="badge badge-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span></td>
                        <td>{{ $booking->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">No booking requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($user->isResourceManager())
        <!-- Manager specific -->
        <h2 class="section-title">Resource Management Overview</h2>
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_managed_resources'] ?? 0 }}</div>
                <div class="stat-label">Managed Resources</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['pending_approvals'] ?? 0 }}</div>
                <div class="stat-label">Pending Approvals</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['approved_requests'] ?? 0 }}</div>
                <div class="stat-label">Approved Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['rejected_requests'] ?? 0 }}</div>
                <div class="stat-label">Rejected Requests</div>
            </div>
        </div>

        <h2 class="section-title">Recent Approval Activity</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Resource</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Date Processed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_activity'] ?? [] as $activity)
                    <tr>
                        <td style="font-weight: 500;">{{ $activity->user->name }}</td>
                        <td>{{ $activity->resource->name }}</td>
                        <td>{{ $activity->start_datetime->diffInHours($activity->end_datetime) }} hour(s)</td>
                        <td><span class="badge badge-{{ strtolower($activity->status) }}">{{ ucfirst($activity->status) }}</span></td>
                        <td>{{ $activity->updated_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted);">No recent approval activity.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($user->isAdmin())
        <!-- Admin specific -->
        <h2 class="section-title">System Overview</h2>
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_employees'] ?? 0 }}</div>
                <div class="stat-label">Employees</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_managers'] ?? 0 }}</div>
                <div class="stat-label">Resource Managers</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_departments'] ?? 0 }}</div>
                <div class="stat-label">Departments</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['active_users'] ?? 0 }}</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>
    @endif
</div>
@endsection
