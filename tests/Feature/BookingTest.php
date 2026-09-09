<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_booking_successfully()
    {
        $user = \App\Models\User::factory()->create();
        
        $category = \App\Models\ResourceCategory::create([
            'name' => 'Meeting Rooms',
            'approval_type' => 'NONE'
        ]);

        $resource = \App\Models\Resource::create([
            'category_id' => $category->id,
            'name' => 'Room A',
            'code' => 'ROOM-A',
            'status' => 'AVAILABLE',
            'approval_type' => 'NONE',
            'capacity' => 10
        ]);
        
        $response = $this->actingAs($user)->post('/bookings', [
            'resource_id' => $resource->id,
            'start_datetime' => now()->addDay()->setHour(10)->setMinute(0)->format('Y-m-d H:i:s'),
            'end_datetime' => now()->addDay()->setHour(11)->setMinute(0)->format('Y-m-d H:i:s'),
            'purpose' => 'Team Meeting',
            'participants' => 5,
            'priority' => 'NORMAL',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('bookings', [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'purpose' => 'Team Meeting'
        ]);
    }

    public function test_system_prevents_double_booking_overlap()
    {
        $user1 = \App\Models\User::factory()->create();
        $user2 = \App\Models\User::factory()->create();
        
        $category = \App\Models\ResourceCategory::create([
            'name' => 'Meeting Rooms',
            'approval_type' => 'NONE'
        ]);

        $resource = \App\Models\Resource::create([
            'category_id' => $category->id,
            'name' => 'Room A',
            'code' => 'ROOM-A',
            'status' => 'AVAILABLE',
            'approval_type' => 'NONE',
            'capacity' => 10
        ]);

        // First user books 10 AM to 12 PM and gets approved
        \App\Models\Booking::create([
            'resource_id' => $resource->id,
            'user_id' => $user1->id,
            'start_datetime' => now()->addDay()->setHour(10)->setMinute(0),
            'end_datetime' => now()->addDay()->setHour(12)->setMinute(0),
            'purpose' => 'First Meeting',
            'participants' => 2,
            'priority' => 'NORMAL',
            'status' => 'APPROVED',
            'approval_type' => 'NONE'
        ]);

        // Second user tries to book 11 AM to 1 PM (overlap)
        $response = $this->actingAs($user2)->post('/bookings', [
            'resource_id' => $resource->id,
            'start_datetime' => now()->addDay()->setHour(11)->setMinute(0)->format('Y-m-d H:i:s'),
            'end_datetime' => now()->addDay()->setHour(13)->setMinute(0)->format('Y-m-d H:i:s'),
            'purpose' => 'Overlapping Meeting',
            'participants' => 2,
            'priority' => 'NORMAL',
        ]);

        $response->assertSessionHasErrors(['start_datetime']);
        $this->assertDatabaseMissing('bookings', [
            'purpose' => 'Overlapping Meeting'
        ]);
    }
}
