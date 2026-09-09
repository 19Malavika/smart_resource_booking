<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceMaintenance;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = ResourceMaintenance::with(['resource', 'creator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resource', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $maintenances = $query->latest()->paginate(15)->withQueryString();

        return view('admin.maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        $resources = Resource::orderBy('name')->get();
        return view('admin.maintenance.create', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:SCHEDULED,IN_PROGRESS,COMPLETED',
        ]);

        $validated['created_by'] = auth()->id();

        $maintenance = ResourceMaintenance::create($validated);

        // Optionally set resource to MAINTENANCE status
        if ($validated['status'] === 'IN_PROGRESS') {
            $maintenance->resource->update(['status' => 'MAINTENANCE']);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_maintenance',
            'auditable_type' => ResourceMaintenance::class,
            'auditable_id' => $maintenance->id,
            'new_values' => ['resource' => $maintenance->resource->name, 'start' => $validated['start_datetime'], 'end' => $validated['end_datetime']],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance scheduled successfully.');
    }

    public function show(ResourceMaintenance $maintenance)
    {
        $maintenance->load(['resource', 'creator']);
        return view('admin.maintenance.show', compact('maintenance'));
    }

    public function edit(ResourceMaintenance $maintenance)
    {
        $resources = Resource::orderBy('name')->get();
        return view('admin.maintenance.edit', compact('maintenance', 'resources'));
    }

    public function update(Request $request, ResourceMaintenance $maintenance)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'reason' => 'required|string|max:500',
            'status' => 'required|in:SCHEDULED,IN_PROGRESS,COMPLETED',
        ]);

        $oldValues = $maintenance->only(['status', 'reason']);
        $maintenance->update($validated);

        // Handle resource status based on maintenance status
        if ($validated['status'] === 'COMPLETED') {
            $maintenance->resource->update(['status' => 'AVAILABLE']);
        } elseif ($validated['status'] === 'IN_PROGRESS') {
            $maintenance->resource->update(['status' => 'MAINTENANCE']);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated_maintenance',
            'auditable_type' => ResourceMaintenance::class,
            'auditable_id' => $maintenance->id,
            'old_values' => $oldValues,
            'new_values' => ['status' => $validated['status']],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance record updated.');
    }

    public function destroy(ResourceMaintenance $maintenance)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted_maintenance',
            'auditable_type' => ResourceMaintenance::class,
            'auditable_id' => $maintenance->id,
            'old_values' => ['resource' => $maintenance->resource->name],
            'ip_address' => request()->ip(),
        ]);

        $maintenance->delete();

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance record deleted.');
    }
}
