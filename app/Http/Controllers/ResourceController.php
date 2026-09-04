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

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // If the user is a manager (but not admin), optionally filter by their resources
        if (auth()->user()->isResourceManager() && !auth()->user()->isAdmin()) {
            $query->where('manager_id', auth()->id());
        }

        $resources = $query->paginate(10);
        $categories = ResourceCategory::all();

        return view('admin.resources.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Resource::class);
        $categories = ResourceCategory::all();
        $managers = User::whereIn('role', ['admin', 'manager'])->get();

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
        return view('admin.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        $categories = ResourceCategory::all();
        $managers = User::whereIn('role', ['admin', 'manager'])->get();

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
