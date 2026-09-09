<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::with(['category', 'manager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $resources = $query->latest()->paginate(15)->withQueryString();
        $categories = ResourceCategory::orderBy('name')->get();

        return view('admin.resources.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $categories = ResourceCategory::orderBy('name')->get();
        $managers = User::where('role', 'manager')->orderBy('name')->get();
        return view('admin.resources.create', compact('categories', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:resources,code',
            'category_id' => 'required|exists:resource_categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:AVAILABLE,UNAVAILABLE,MAINTENANCE',
            'approval_type' => 'required|in:NONE,MANAGER,ADMIN',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }
        unset($validated['image']);

        $resource = Resource::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_resource',
            'auditable_type' => Resource::class,
            'auditable_id' => $resource->id,
            'new_values' => $resource->only(['name', 'code', 'status']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Resource created successfully.');
    }

    public function show(Resource $resource)
    {
        $resource->load(['category', 'manager', 'bookings.user', 'maintenances']);
        $recentBookings = $resource->bookings()->with('user')->latest()->limit(10)->get();
        return view('admin.resources.show', compact('resource', 'recentBookings'));
    }

    public function edit(Resource $resource)
    {
        $categories = ResourceCategory::orderBy('name')->get();
        $managers = User::where('role', 'manager')->orderBy('name')->get();
        return view('admin.resources.edit', compact('resource', 'categories', 'managers'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:resources,code,' . $resource->id,
            'category_id' => 'required|exists:resource_categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:AVAILABLE,UNAVAILABLE,MAINTENANCE',
            'approval_type' => 'required|in:NONE,MANAGER,ADMIN',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $oldValues = $resource->only(['name', 'code', 'status']);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }
        unset($validated['image']);

        $resource->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_resource',
            'auditable_type' => Resource::class,
            'auditable_id' => $resource->id,
            'old_values' => $oldValues,
            'new_values' => $resource->only(['name', 'code', 'status']),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        if ($resource->bookings()->whereIn('status', ['PENDING', 'APPROVED'])->count() > 0) {
            return back()->with('error', 'Cannot delete resource with active bookings.');
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_resource',
            'auditable_type' => Resource::class,
            'auditable_id' => $resource->id,
            'old_values' => $resource->only(['name', 'code']),
            'ip_address' => request()->ip(),
        ]);

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted successfully.');
    }
}
