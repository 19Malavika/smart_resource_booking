@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')

<!-- Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700; color: var(--text-main); letter-spacing: -0.5px;">
            Welcome Back, {{ auth()->user()->name }}!
        </h1>

        <p style="font-size: 14px; color: var(--text-muted); margin-top: 4px;">
            Manage your resource bookings and upcoming reservations.
        </p>
    </div>

    <a href="{{ route('bookings.create') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"></path>
        </svg>
        Book a Resource
    </a>
</div>


<!-- Statistics -->
<div style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
">

    <!-- Available Resources -->
    <div class="card" style="margin-bottom: 0; padding: 20px;">
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <div style="
                    font-size: 13px;
                    color: var(--text-muted);
                    margin-bottom: 8px;
                ">
                    Available Resources
                </div>

                <div style="
                    font-size: 28px;
                    font-weight: 700;
                    color: var(--text-main);
                ">
                    {{ $availableResources }}
                </div>
            </div>

            <div style="
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: #eff6ff;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #2563eb;
            ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="21" height="21">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M8 11h8"></path>
                </svg>
            </div>
        </div>
    </div>


    <!-- Pending -->
    <div class="card" style="margin-bottom: 0; padding: 20px;">
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <div style="
                    font-size: 13px;
                    color: var(--text-muted);
                    margin-bottom: 8px;
                ">
                    Pending Requests
                </div>

                <div style="
                    font-size: 28px;
                    font-weight: 700;
                    color: var(--text-main);
                ">
                    {{ $pendingCount }}
                </div>
            </div>

            <div style="
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: #fff7ed;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ea580c;
            ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="21" height="21">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>


    <!-- Approved -->
    <div class="card" style="margin-bottom: 0; padding: 20px;">
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <div style="
                    font-size: 13px;
                    color: var(--text-muted);
                    margin-bottom: 8px;
                ">
                    Approved Bookings
                </div>

                <div style="
                    font-size: 28px;
                    font-weight: 700;
                    color: var(--text-main);
                ">
                    {{ $approvedCount }}
                </div>
            </div>

            <div style="
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: #ecfdf5;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #059669;
            ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="21" height="21">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>


    <!-- Rejected -->
    <div class="card" style="margin-bottom: 0; padding: 20px;">
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        ">
            <div>
                <div style="
                    font-size: 13px;
                    color: var(--text-muted);
                    margin-bottom: 8px;
                ">
                    Rejected Requests
                </div>

                <div style="
                    font-size: 28px;
                    font-weight: 700;
                    color: var(--text-main);
                ">
                    {{ $rejectedCount }}
                </div>
            </div>

            <div style="
                width: 42px;
                height: 42px;
                border-radius: 8px;
                background: #fef2f2;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #dc2626;
            ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="21" height="21">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
    </div>

</div>


<!-- Upcoming Bookings -->
<div class="card" style="padding: 0;">

    <div class="card-header"
         style="
            padding: 24px 24px 16px 24px;
            margin-bottom: 0;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
         ">

        <div>
            <h2 class="card-title">
                Upcoming Bookings
            </h2>

            <p style="
                font-size: 13px;
                color: var(--text-muted);
                margin-top: 4px;
            ">
                Your upcoming approved resource reservations.
            </p>
        </div>

        <a href="{{ route('bookings.index') }}"
           style="
                font-size: 13px;
                color: #2563eb;
                text-decoration: none;
                font-weight: 600;
           ">
            View All
        </a>

    </div>


    <div class="table-container"
         style="
            border: none;
            border-top: 1px solid var(--border-light);
            border-radius: 0 0 8px 8px;
         ">

        <table class="data-table">

            <thead>
                <tr>
                    <th>Resource</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Purpose</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($upcomingBookings as $booking)

                <tr>

                    <!-- Resource -->
                    <td>
                        <div style="font-weight: 600;">
                            {{ $booking->resource->name }}
                        </div>

                        <div style="
                            font-size: 12px;
                            color: var(--text-muted);
                            margin-top: 3px;
                        ">
                            {{ $booking->resource->location }}
                        </div>
                    </td>


                    <!-- Start -->
                    <td>
                        {{ $booking->start_datetime->format('d M, Y') }}

                        <div style="
                            font-size: 12px;
                            color: var(--text-muted);
                            margin-top: 3px;
                        ">
                            {{ $booking->start_datetime->format('h:i A') }}
                        </div>
                    </td>


                    <!-- End -->
                    <td>
                        {{ $booking->end_datetime->format('d M, Y') }}

                        <div style="
                            font-size: 12px;
                            color: var(--text-muted);
                            margin-top: 3px;
                        ">
                            {{ $booking->end_datetime->format('h:i A') }}
                        </div>
                    </td>


                    <!-- Purpose -->
                    <td style="max-width: 250px;">
                        <span style="color: var(--text-muted);">
                            {{ Str::limit($booking->purpose, 40) }}
                        </span>
                    </td>


                    <!-- Priority -->
                    <td>
                        <span class="badge badge-{{ $booking->priority }}">
                            {{ ucfirst($booking->priority) }}
                        </span>
                    </td>


                    <!-- Status -->
                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6"
                        style="
                            text-align: center;
                            color: var(--text-muted);
                            padding: 40px 0;
                            font-size: 13px;
                        ">

                        <div style="margin-bottom: 12px;">

                            <svg fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 width="32"
                                 height="32"
                                 style="color: #94a3b8;">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>

                            </svg>

                        </div>

                        You don't have any upcoming bookings.

                        <div style="margin-top: 12px;">
                            <a href="{{ route('bookings.create') }}"
                               class="btn btn-primary"
                               style="display: inline-flex;">
                                Book a Resource
                            </a>
                        </div>

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- Recent Bookings -->
<div class="card" style="padding: 0; margin-top: 24px;">

    <div class="card-header"
         style="
            padding: 24px 24px 16px 24px;
            margin-bottom: 0;
         ">

        <h2 class="card-title">
            Recent Booking Requests
        </h2>

    </div>


    <div class="table-container"
         style="
            border: none;
            border-top: 1px solid var(--border-light);
         ">

        <table class="data-table">

            <thead>

                <tr>
                    <th>Resource</th>
                    <th>Date</th>
                    <th>Purpose</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

            </thead>


            <tbody>

                @forelse($myBookings->take(6) as $booking)

                <tr>

                    <td style="font-weight: 500;">
                        {{ $booking->resource->name }}
                    </td>

                    <td>
                        {{ $booking->start_datetime->format('d M, Y') }}
                    </td>

                    <td>
                        <span style="color: var(--text-muted);">
                            {{ Str::limit($booking->purpose, 40) }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $booking->priority }}">
                            {{ ucfirst($booking->priority) }}
                        </span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('bookings.show', $booking) }}"
                           style="
                                color: #2563eb;
                                font-size: 12px;
                                font-weight: 600;
                                text-decoration: none;
                           ">
                            View
                        </a>
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6"
                        style="
                            text-align: center;
                            color: var(--text-muted);
                            padding: 32px 0;
                            font-size: 13px;
                        ">

                        You haven't submitted any booking requests yet.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection