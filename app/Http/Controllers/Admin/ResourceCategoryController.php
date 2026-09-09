<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceCategory;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ResourceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ResourceCategory::withCount('resources');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $categories = $query->latest()->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:resource_categories,name',
            'description' => 'nullable|string|max:500',
            'approval_type' => 'required|in:NONE,MANAGER,ADMIN',
        ]);

        $category = ResourceCategory::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_category',
            'auditable_type' => ResourceCategory::class,
            'auditable_id' => $category->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(ResourceCategory $category)
    {
        $category->load('resources');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(ResourceCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ResourceCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:resource_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'approval_type' => 'required|in:NONE,MANAGER,ADMIN',
        ]);

        $oldValues = $category->only(['name', 'description', 'approval_type']);
        $category->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_category',
            'auditable_type' => ResourceCategory::class,
            'auditable_id' => $category->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ResourceCategory $category)
    {
        if ($category->resources()->count() > 0) {
            return back()->with('error', 'Cannot delete category with assigned resources.');
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_category',
            'auditable_type' => ResourceCategory::class,
            'auditable_id' => $category->id,
            'old_values' => $category->only(['name']),
            'ip_address' => request()->ip(),
        ]);

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
