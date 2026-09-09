<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceAvailability;
use Illuminate\Http\Request;

class ResourceAvailabilityController extends Controller
{
    public function update(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $request->validate([
            'availabilities' => ['required', 'array'],
            'availabilities.*.day_of_week' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'availabilities.*.is_available' => ['required', 'boolean'],
            'availabilities.*.start_time' => ['nullable', 'required_if:availabilities.*.is_available,1', 'date_format:H:i'],
            'availabilities.*.end_time' => ['nullable', 'required_if:availabilities.*.is_available,1', 'date_format:H:i', 'after:availabilities.*.start_time'],
        ]);

        foreach ($validated['availabilities'] as $data) {
            ResourceAvailability::updateOrCreate(
                ['resource_id' => $resource->id, 'day_of_week' => $data['day_of_week']],
                [
                    'is_available' => $data['is_available'],
                    'start_time' => $data['is_available'] ? $data['start_time'] : null,
                    'end_time' => $data['is_available'] ? $data['end_time'] : null,
                ]
            );
        }

        return back()->with('success', 'Resource working hours updated successfully.');
    }
}
