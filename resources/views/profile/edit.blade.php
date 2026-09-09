@extends('layouts.app')
@section('title', 'Account Settings')
@section('content')
<div class="reveal-up max-w-3xl mx-auto space-y-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-zinc-50">Account Settings</h1>
        <p class="text-sm text-zinc-400">Update your profile information and password.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg text-sm">
            Profile updated successfully.
        </div>
    @endif

    <!-- Profile Information Form -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-zinc-50 mb-4">Profile Information</h2>
        <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-control">
                @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-control">
                @error('email')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-6 shadow-sm mt-6">
        <h2 class="text-lg font-semibold text-zinc-50 mb-2">Delete Account</h2>
        <p class="text-sm text-zinc-400 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        
        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
            @csrf
            @method('delete')

            <div>
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password to confirm" required class="form-control">
                @error('password', 'userDeletion')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
