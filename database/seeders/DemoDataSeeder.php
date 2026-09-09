<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResourceMaintenance;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\User;
use App\Models\Resource;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
        $employee = User::where('role', 'employee')->first() ?? User::factory()->create(['role' => 'employee']);
        
        $resources = Resource::take(5)->get();
        if ($resources->isEmpty()) {
            return;
        }

        // 1. Seed Maintenance / Unavailable data
        foreach ($resources->take(3) as $resource) {
            ResourceMaintenance::create([
                'resource_id' => $resource->id,
                'start_datetime' => Carbon::now()->addDays(rand(1, 5))->setHour(10)->setMinute(0),
                'end_datetime' => Carbon::now()->addDays(rand(1, 5))->setHour(14)->setMinute(0),
                'reason' => 'Routine maintenance and cleaning.',
                'status' => 'SCHEDULED',
                'created_by' => $admin->id,
            ]);
            
            ResourceMaintenance::create([
                'resource_id' => $resource->id,
                'start_datetime' => Carbon::now()->subDays(rand(1, 5))->setHour(9)->setMinute(0),
                'end_datetime' => Carbon::now()->subDays(rand(1, 5))->setHour(12)->setMinute(0),
                'reason' => 'Equipment issue reported.',
                'status' => 'COMPLETED',
                'created_by' => $admin->id,
            ]);
        }

        // 2. Seed Audit Logs
        $actions = ['CREATED', 'UPDATED', 'DELETED', 'APPROVED', 'REJECTED'];
        for ($i = 0; $i < 15; $i++) {
            $resource = $resources->random();
            AuditLog::create([
                'user_id' => $admin->id,
                'action' => $actions[array_rand($actions)],
                'auditable_type' => Resource::class,
                'auditable_id' => $resource->id,
                'old_values' => ['status' => 'AVAILABLE'],
                'new_values' => ['status' => 'UNAVAILABLE'],
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subHours(rand(1, 100)),
            ]);
        }

        // 3. Seed Resource Utilization (Bookings)
        for ($i = 0; $i < 20; $i++) {
            $resource = $resources->random();
            $start = Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 15))->setMinute(0);
            $end = (clone $start)->addHours(rand(1, 4));
            
            Booking::create([
                'user_id' => $employee->id,
                'resource_id' => $resource->id,
                'start_datetime' => $start,
                'end_datetime' => $end,
                'purpose' => 'Client Meeting / Project Work',
                'priority' => 'NORMAL',
                'status' => 'APPROVED',
                'approval_type' => 'NONE',
                'approved_by' => $admin->id,
                'approved_at' => (clone $start)->subDay(),
            ]);
        }
    }
}
