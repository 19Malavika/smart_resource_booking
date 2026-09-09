@extends('layouts.app')
@section('title', 'All Bookings')
@section('content')

<div class="reveal-up max-w-7xl mx-auto">


<div class="flex justify-between items-end mb-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-50 mb-1">All Bookings</h1>
        <p class="text-sm text-zinc-400">
            View and manage all booking requests across the system
        </p>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        {{ session('error') }}
    </div>
@endif

{{-- Filters --}}
<div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 mb-6">
    <form method="GET"
          action="{{ route('admin.bookings.index') }}"
          class="flex flex-wrap gap-3 items-end">

        {{-- Search --}}
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs text-zinc-500 mb-1">
                Search
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="User or resource name..."
                class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm placeholder-zinc-500 focus:outline-none focus:border-zinc-500"
            >
        </div>

        {{-- Status --}}
        <div class="w-36">
            <label class="block text-xs text-zinc-500 mb-1">
                Status
            </label>

            <select
                name="status"
                class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"
            >
                <option value="">All</option>

                @foreach(['PENDING','APPROVED','REJECTED','CANCELLED','COMPLETED','EXPIRED'] as $s)
                    <option
                        value="{{ $s }}"
                        {{ request('status') == $s ? 'selected' : '' }}
                    >
                        {{ ucfirst(strtolower($s)) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- From --}}
        <div class="w-40">
            <label class="block text-xs text-zinc-500 mb-1">
                From
            </label>

            <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"
            >
        </div>

        {{-- To --}}
        <div class="w-40">
            <label class="block text-xs text-zinc-500 mb-1">
                To
            </label>

            <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="w-full px-3 py-2 rounded-lg bg-zinc-900 border border-zinc-700 text-zinc-200 text-sm focus:outline-none focus:border-zinc-500"
            >
        </div>

        {{-- Filter --}}
        <button
            type="submit"
            class="px-4 py-2 rounded-lg text-sm font-medium bg-zinc-800 border border-zinc-700 text-zinc-200 hover:bg-zinc-700 transition-colors"
        >
            Filter
        </button>

        {{-- Clear --}}
        <a
            href="{{ route('admin.bookings.index') }}"
            class="px-4 py-2 rounded-lg text-sm text-zinc-400 hover:text-zinc-200"
        >
            Clear
        </a>

    </form>
</div>

{{-- Bookings Table --}}
<div class="bg-zinc-950 border border-zinc-800 rounded-xl overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm text-left">

            <thead>
                <tr class="border-b border-zinc-800 bg-zinc-900/30">

                    <th class="px-5 py-3 font-medium text-zinc-400">
                        Resource
                    </th>

                    <th class="px-5 py-3 font-medium text-zinc-400">
                        Employee
                    </th>

                    <th class="px-5 py-3 font-medium text-zinc-400">
                        Date & Time
                    </th>

                    <th class="px-5 py-3 font-medium text-zinc-400">
                        Priority
                    </th>

                    <th class="px-5 py-3 font-medium text-zinc-400">
                        Status
                    </th>

                    <th class="px-5 py-3 font-medium text-zinc-400 text-right">
                        Actions
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse($bookings as $booking)

                    <tr class="border-b border-zinc-800 last:border-0 hover:bg-zinc-900/30 transition-colors">

                        {{-- Resource --}}
                        <td class="px-5 py-3">

                            <div class="font-medium text-zinc-200">
                                {{ $booking->resource->name }}
                            </div>

                            <div class="text-xs text-zinc-500 mt-0.5">
                                {{ $booking->resource->category->name ?? 'Resource' }}
                            </div>

                        </td>

                        {{-- Employee --}}
                        <td class="px-5 py-3 text-zinc-300">
                            {{ $booking->user->name }}
                        </td>

                        {{-- Date & Time --}}
                        <td class="px-5 py-3">

                            <div class="text-zinc-300">
                                {{ $booking->start_datetime->format('M j, Y') }}
                            </div>

                            <div class="text-xs text-zinc-500 mt-0.5">
                                {{ $booking->start_datetime->format('H:i') }}
                                -
                                {{ $booking->end_datetime->format('H:i') }}
                            </div>

                        </td>

                        {{-- Priority --}}
                        <td class="px-5 py-3">

                            @php
                                $pClass = match($booking->priority) {
                                    'URGENT' => 'text-red-400',
                                    'HIGH' => 'text-amber-400',
                                    'NORMAL' => 'text-zinc-300',
                                    'LOW' => 'text-zinc-500',
                                    default => 'text-zinc-400',
                                };
                            @endphp

                            <span class="text-xs font-medium {{ $pClass }}">
                                {{ $booking->priority }}
                            </span>

                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3">

                            @php
                                $sClasses = match(strtolower($booking->status)) {

                                    'approved' =>
                                        'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',

                                    'pending' =>
                                        'bg-amber-500/10 text-amber-400 border-amber-500/20',

                                    'rejected' =>
                                        'bg-red-500/10 text-red-400 border-red-500/20',

                                    'cancelled' =>
                                        'bg-zinc-800 text-zinc-400 border-zinc-700',

                                    'completed' =>
                                        'bg-blue-500/10 text-blue-400 border-blue-500/20',

                                    'expired' =>
                                        'bg-zinc-800/70 text-zinc-500 border-zinc-700',

                                    default =>
                                        'bg-zinc-800 text-zinc-300 border-zinc-700',
                                };
                            @endphp

                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border capitalize {{ $sClasses }}">
                                {{ strtolower($booking->status) }}
                            </span>

                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-3 text-right">

                            <a
                                href="{{ route('admin.bookings.show', $booking) }}"
                                class="inline-flex items-center justify-center p-1.5 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"
                                title="View Details"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    ></path>

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7 0 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    ></path>
                                </svg>

                            </a>



                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="6"
                            class="text-center text-zinc-500 py-10"
                        >
                            No bookings found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- Pagination --}}
<div class="mt-4">
    {{ $bookings->links() }}
</div>

</div>

@endsection
