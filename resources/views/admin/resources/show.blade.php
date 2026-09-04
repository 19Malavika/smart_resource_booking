@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6 flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('resources.index') }}" class="hover:text-gray-900 transition-colors">Resources</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $resource->code }}</span>
            </div>
            <h1 class="text-3xl font-semibold text-gray-900 tracking-tight">{{ $resource->name }}</h1>
        </div>
        <div class="flex space-x-3">
            @can('update', $resource)
            <a href="{{ route('resources.edit', $resource) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Edit Resource
            </a>
            @endcan
            @can('delete', $resource)
            <form action="{{ route('resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resource?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-lg overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-5">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Resource Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Details, capacity, and current status.</p>
        </div>
        <div class="px-6 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Resource Code</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 font-mono bg-gray-100 px-2 py-1 rounded inline-block">{{ $resource->code }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $resource->category->name }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $resource->status === 'AVAILABLE' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $resource->status === 'UNAVAILABLE' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $resource->status === 'MAINTENANCE' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $resource->status === 'INACTIVE' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $resource->status }}
                        </span>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $resource->capacity }} participants</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $resource->location ?? 'Not specified' }}</dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Resource Manager</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        @if($resource->manager)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3">
                                    {{ substr($resource->manager->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $resource->manager->name }}</div>
                                    <div class="text-gray-500">{{ $resource->manager->email }}</div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-400 italic">No manager assigned</span>
                        @endif
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Approval Requirement</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        {{ $resource->approval_type === 'NONE' ? 'Automatic Approval' : ($resource->approval_type === 'MANAGER' ? 'Manager Approval' : 'Admin Approval') }}
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $resource->description ?: 'No description provided.' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
