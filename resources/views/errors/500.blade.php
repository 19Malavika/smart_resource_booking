@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
    <div class="mb-8">
        <svg class="w-32 h-32 text-red-500/20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
    </div>
    
    <h1 class="text-6xl font-bold text-zinc-50 mb-4 tracking-tight">500</h1>
    <h2 class="text-2xl font-semibold text-zinc-300 mb-4">Internal Server Error</h2>
    
    <p class="text-zinc-400 mb-8 max-w-md mx-auto text-[15px] leading-relaxed">
        Something went wrong on our end. Our team has been notified and is working to fix the issue. Please try again later.
    </p>
    
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-lg bg-zinc-800 text-zinc-50 font-semibold text-sm transition-colors hover:bg-zinc-700">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Try Again
    </a>
</div>
@endsection
