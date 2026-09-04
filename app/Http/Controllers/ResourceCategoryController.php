<?php

namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use App\Http\Requests\StoreResourceCategoryRequest;
use App\Http\Requests\UpdateResourceCategoryRequest;

class ResourceCategoryController extends Controller
{
    public function index()
    {
        $categories = ResourceCategory::paginate(15);
        return view('admin.resource-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.resource-categories.create');
    }

    public function store(StoreResourceCategoryRequest $request)
    {
        ResourceCategory::create($request->validated());

        return redirect()->route('resource-categories.index')
            ->with('success', 'Resource category created successfully.');
    }

    public function edit(ResourceCategory $resourceCategory)
    {
        return view('admin.resource-categories.edit', compact('resourceCategory'));
    }

    public function update(UpdateResourceCategoryRequest $request, ResourceCategory $resourceCategory)
    {
        $resourceCategory->update($request->validated());

        return redirect()->route('resource-categories.index')
            ->with('success', 'Resource category updated successfully.');
    }

    public function destroy(ResourceCategory $resourceCategory)
    {
        $this->authorize('delete', $resourceCategory);
        $resourceCategory->delete();

        return redirect()->route('resource-categories.index')
            ->with('success', 'Resource category deleted successfully.');
    }
}
