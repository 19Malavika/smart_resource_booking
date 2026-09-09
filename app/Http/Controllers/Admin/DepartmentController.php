<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount('employees');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string|max:500',
        ]);

        $department = Department::create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_department',
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load('employees');
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'description' => 'nullable|string|max:500',
        ]);

        $oldValues = $department->only(['name', 'code', 'description']);
        $department->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_department',
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department with assigned employees.');
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_department',
            'auditable_type' => Department::class,
            'auditable_id' => $department->id,
            'old_values' => $department->only(['name', 'code']),
            'ip_address' => request()->ip(),
        ]);

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }
}
