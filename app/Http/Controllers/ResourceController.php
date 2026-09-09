<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Resource::class);

        $query = Resource::with(['category', 'manager']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }
        
        // If the user is a manager (but not admin), optionally filter by their resources
        if (auth()->user()->isResourceManager() && !auth()->user()->isAdmin()) {
            $query->where('manager_id', auth()->id());
        }

        $resources = $query->paginate(10)->withQueryString();
        $categories = ResourceCategory::all();

        return view('admin.resources.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Resource::class);
        $categories = ResourceCategory::all();
        $managers = User::whereIn('role', ['admin', 'resource_manager'])->get();

        return view('admin.resources.create', compact('categories', 'managers'));
    }

    public function store(StoreResourceRequest $request)
    {
        Resource::create($request->validated());

        return redirect()->route('resources.index')
            ->with('success', 'Resource created successfully.');
    }

    public function show(Resource $resource)
    {
        $this->authorize('view', $resource);
        
        $resource->load(['category', 'manager', 'bookings.user', 'maintenances']);
        $recentBookings = $resource->bookings()->with('user')->latest()->limit(10)->get();
        
        return view('admin.resources.show', compact('resource', 'recentBookings'));
    }

    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        $categories = ResourceCategory::all();
        $managers = User::whereIn('role', ['admin', 'resource_manager'])->get();

        return view('admin.resources.edit', compact('resource', 'categories', 'managers'));
    }

    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $resource->update($request->validated());

        return redirect()->route('resources.index')
            ->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);
        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Resource deleted successfully.');
    }
}
