<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Holiday;
use App\Models\Resource;
use App\Models\ResourceMaintenance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UtilizationService
{
    /**
     * Get comprehensive utilization stats for a resource over a given period.
     */
    public function getResourceStats(Resource $resource, Carbon $start, Carbon $end): array
    {
        $availableSeconds = $this->calculateAvailableSeconds($resource, $start, $end);
        $bookedSeconds = $this->calculateBookedSeconds($resource, $start, $end);
        
        // Ensure we don't exceed 100% or divide by zero
        $utilization = $availableSeconds > 0 ? min(100, round(($bookedSeconds / $availableSeconds) * 100, 1)) : 0;
        
        $bookings = Booking::where('resource_id', $resource->id)
            ->whereIn('status', ['APPROVED', 'COMPLETED'])
            ->where(function ($query) use ($start, $end) {
                $query->where('start_datetime', '<', $end)
                      ->where('end_datetime', '>', $start);
            })
            ->get();
            
        $maintenanceSeconds = $this->calculateMaintenanceSeconds($resource, $start, $end);

        return [
            'total_available_hours' => round($availableSeconds / 3600, 1),
            'total_booked_hours' => round($bookedSeconds / 3600, 1),
            'total_maintenance_hours' => round($maintenanceSeconds / 3600, 1),
            'total_bookings' => $bookings->count(),
            'utilization_percentage' => $utilization,
        ];
    }

    /**
     * Calculate true available working seconds (excluding holidays and maintenance).
     */
    public function calculateAvailableSeconds(Resource $resource, Carbon $start, Carbon $end): int
    {
        $availabilities = $resource->availabilities()->where('is_available', true)->get()->keyBy('day_of_week');
        
        $holidays = Holiday::where('date', '>=', $start->toDateString())
            ->where('date', '<=', $end->toDateString())
            ->pluck('date')
            ->map(fn($date) => Carbon::parse($date)->toDateString())
            ->toArray();

        $maintenances = ResourceMaintenance::where('resource_id', $resource->id)
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->get();

        $totalSeconds = 0;
        $period = CarbonPeriod::create($start->copy()->startOfDay(), '1 day', $end->copy()->endOfDay());

        foreach ($period as $date) {
            if ($date > $end) break;
            
            // Skip holidays
            if (in_array($date->toDateString(), $holidays)) {
                continue;
            }

            // MySQL day of week (1=Sunday, 2=Monday, etc.) or just map from carbon
            $dayOfWeek = $date->isSunday() ? 7 : $date->dayOfWeek;

            if ($availabilities->has($dayOfWeek)) {
                $rule = $availabilities->get($dayOfWeek);
                
                $workStart = $date->copy()->setTimeFromTimeString($rule->start_time);
                $workEnd = $date->copy()->setTimeFromTimeString($rule->end_time);
                
                // Adjust for start/end period bounds
                $workStart = max($workStart, $start);
                $workEnd = min($workEnd, $end);

                if ($workEnd > $workStart) {
                    $dayAvailableSeconds = $workStart->diffInSeconds($workEnd);

                    // Subtract maintenance time that overlaps with THESE working hours
                    foreach ($maintenances as $maintenance) {
                        $mStart = max($workStart, Carbon::parse($maintenance->start_datetime));
                        $mEnd = min($workEnd, Carbon::parse($maintenance->end_datetime));

                        if ($mEnd > $mStart) {
                            $dayAvailableSeconds -= $mStart->diffInSeconds($mEnd);
                        }
                    }

                    $totalSeconds += max(0, $dayAvailableSeconds);
                }
            }
        }

        return $totalSeconds;
    }

    /**
     * Calculate seconds resource is booked.
     */
    public function calculateBookedSeconds(Resource $resource, Carbon $start, Carbon $end): int
    {
        $bookings = Booking::where('resource_id', $resource->id)
            ->whereIn('status', ['APPROVED', 'COMPLETED'])
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->get();

        $totalSeconds = 0;

        foreach ($bookings as $booking) {
            $bStart = max($start, Carbon::parse($booking->start_datetime));
            $bEnd = min($end, Carbon::parse($booking->end_datetime));

            if ($bEnd > $bStart) {
                $totalSeconds += $bStart->diffInSeconds($bEnd);
            }
        }

        return $totalSeconds;
    }
    
    /**
     * Calculate seconds resource is under maintenance during the period.
     */
    public function calculateMaintenanceSeconds(Resource $resource, Carbon $start, Carbon $end): int
    {
        $maintenances = ResourceMaintenance::where('resource_id', $resource->id)
            ->where('start_datetime', '<', $end)
            ->where('end_datetime', '>', $start)
            ->get();

        $totalSeconds = 0;

        foreach ($maintenances as $maintenance) {
            $mStart = max($start, Carbon::parse($maintenance->start_datetime));
            $mEnd = min($end, Carbon::parse($maintenance->end_datetime));

            if ($mEnd > $mStart) {
                $totalSeconds += $mStart->diffInSeconds($mEnd);
            }
        }

        return $totalSeconds;
    }
}
