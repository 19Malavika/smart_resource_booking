<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;

class BookingExampleSeeder extends Seeder
{
    public function run(): void
    {
        $employee = User::where('role', 'employee')->first() ?? User::factory()->create(['role' => 'employee']);
        $manager = User::where('role', 'manager')->first() ?? User::factory()->create(['role' => 'manager']);
        
        $car = Resource::where('code', 'CAR-01')->first();
        $room = Resource::where('code', 'CONF-01')->first();
        $projector = Resource::where('code', 'PROJ-01')->first();

        if (!$car || !$room || !$projector) return;

        // 1. Pending Booking
        Booking::create([
            'user_id' => $employee->id,
            'resource_id' => $car->id,
            'start_datetime' => now()->addDays(2)->setHour(9)->setMinute(0),
            'end_datetime' => now()->addDays(2)->setHour(17)->setMinute(0),
            'purpose' => 'Client Visit (Example Pending Request)',
            'participants' => 2,
            'priority' => 'HIGH',
            'status' => 'PENDING',
        ]);

        // 2. Approved Booking
        Booking::create([
            'user_id' => $employee->id,
            'resource_id' => $room->id,
            'start_datetime' => now()->addDays(3)->setHour(10)->setMinute(0),
            'end_datetime' => now()->addDays(3)->setHour(12)->setMinute(0),
            'purpose' => 'Quarterly Meeting (Example Approved)',
            'participants' => 15,
            'priority' => 'NORMAL',
            'status' => 'APPROVED',
            'approved_by' => $manager->id,
            'approved_at' => now(),
        ]);

        // 3. Rejected Booking
        Booking::create([
            'user_id' => $employee->id,
            'resource_id' => $room->id,
            'start_datetime' => now()->addDays(1)->setHour(14)->setMinute(0),
            'end_datetime' => now()->addDays(1)->setHour(15)->setMinute(0),
            'purpose' => 'Quick Sync (Example Rejected)',
            'participants' => 4,
            'priority' => 'NORMAL',
            'status' => 'REJECTED',
        ]);

        // 4. Cancelled Booking
        Booking::create([
            'user_id' => $employee->id,
            'resource_id' => $projector->id,
            'start_datetime' => now()->addDays(4)->setHour(9)->setMinute(0),
            'end_datetime' => now()->addDays(4)->setHour(11)->setMinute(0),
            'purpose' => 'Presentation (Example Cancelled)',
            'participants' => 1,
            'priority' => 'NORMAL',
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
        ]);
    }
}
