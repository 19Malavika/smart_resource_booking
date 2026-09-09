@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
    <div class="mb-8">
        <svg class="w-32 h-32 text-zinc-800 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
    </div>
    
    <h1 class="text-6xl font-bold text-zinc-50 mb-4 tracking-tight">404</h1>
    <h2 class="text-2xl font-semibold text-zinc-300 mb-4">Page Not Found</h2>
    
    <p class="text-zinc-400 mb-8 max-w-md mx-auto text-[15px] leading-relaxed">
        Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>
    
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center h-11 px-6 rounded-lg bg-zinc-50 text-zinc-950 font-semibold text-sm transition-colors hover:bg-zinc-200 hover:scale-105 active:scale-95">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Homepage
    </a>
</div>
@endsection
