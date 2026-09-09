<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceMaintenance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ResourceMaintenanceController extends Controller
{
    public function store(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $request->validate([
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(['SCHEDULED', 'ACTIVE'])],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($resource, $validated) {
            // Lock the resource for update to prevent concurrent booking conflicts
            $lockedResource = Resource::lockForUpdate()->findOrFail($resource->id);

            $start = Carbon::parse($validated['start_datetime']);
            $end = Carbon::parse($validated['end_datetime']);

            // Check for conflicting bookings
            $conflict = \App\Models\Booking::where('resource_id', $lockedResource->id)
                ->whereIn('status', ['APPROVED', 'PENDING'])
                ->where('start_datetime', '<', $end)
                ->where('end_datetime', '>', $start)
                ->first();

            if ($conflict) {
                $timeString = $conflict->start_datetime->format('M j, Y g:i A') . ' to ' . $conflict->end_datetime->format('g:i A');
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_datetime' => "Maintenance conflicts with an existing {$conflict->status} booking from {$timeString}. Please resolve the booking conflict first."
                ]);
            }

            ResourceMaintenance::create([
                'resource_id' => $lockedResource->id,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'reason' => $validated['reason'],
                'status' => $validated['status'],
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Maintenance period scheduled successfully.');
    }

    public function update(Request $request, ResourceMaintenance $maintenance)
    {
        $this->authorize('update', $maintenance->resource);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['SCHEDULED', 'ACTIVE', 'COMPLETED', 'CANCELLED'])],
        ]);

        $maintenance->update($validated);

        return back()->with('success', 'Maintenance status updated.');
    }

    public function destroy(ResourceMaintenance $maintenance)
    {
        $this->authorize('update', $maintenance->resource);
        
        $maintenance->delete();

        return back()->with('success', 'Maintenance period deleted.');
    }
}
