<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResourceCategory;
use App\Models\Resource;
use App\Models\User;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Get an admin or manager to assign to resources
        $manager = User::where('role', 'manager')->first() ?? User::first();
        if (!$manager) return;

        // Ensure categories exist
        $cats = [
            'Meeting Room' => ResourceCategory::firstOrCreate(['name' => 'Meeting Room'], ['description' => 'Meeting and conference rooms']),
            'Hall' => ResourceCategory::firstOrCreate(['name' => 'Hall'], ['description' => 'Large halls']),
            'Training' => ResourceCategory::firstOrCreate(['name' => 'Training'], ['description' => 'Training facilities']),
            'Electronics' => ResourceCategory::firstOrCreate(['name' => 'Electronics'], ['description' => 'Electronic devices']),
            'Media Equipment' => ResourceCategory::firstOrCreate(['name' => 'Media Equipment'], ['description' => 'Photography and media gear']),
            'IT Equipment' => ResourceCategory::firstOrCreate(['name' => 'IT Equipment'], ['description' => 'Laptops and computers']),
            'Vehicle' => ResourceCategory::firstOrCreate(['name' => 'Vehicle'], ['description' => 'Company vehicles']),
            'Specialized Equipment' => ResourceCategory::firstOrCreate(['name' => 'Specialized Equipment'], ['description' => 'High value specialized equipment']),
            'Event Space' => ResourceCategory::firstOrCreate(['name' => 'Event Space'], ['description' => 'Spaces for events']),
        ];

        $resources = [
            ['code' => 'MR-A', 'name' => 'Meeting Room A', 'category' => 'Meeting Room', 'capacity' => 10, 'approval_type' => 'NONE', 'image' => 'images/resources/meeting_room.jpg'],
            ['code' => 'MR-B', 'name' => 'Meeting Room B', 'category' => 'Meeting Room', 'capacity' => 6, 'approval_type' => 'NONE', 'image' => 'images/resources/meeting_room_b.jpg'],
            ['code' => 'CONF-01', 'name' => 'Conference Room', 'category' => 'Meeting Room', 'capacity' => 20, 'approval_type' => 'MANAGER', 'image' => 'images/resources/meeting_room.jpg'],
            ['code' => 'HALL-01', 'name' => 'Seminar Hall', 'category' => 'Hall', 'capacity' => 100, 'approval_type' => 'MANAGER', 'image' => 'images/resources/seminar_hall.jpg'],
            ['code' => 'TRAIN-01', 'name' => 'Training Room', 'category' => 'Training', 'capacity' => 30, 'approval_type' => 'MANAGER', 'image' => 'images/resources/training_room.jpg'],
            ['code' => 'PROJ-01', 'name' => 'Projector 01', 'category' => 'Electronics', 'capacity' => 1, 'approval_type' => 'NONE', 'image' => 'images/resources/projector.jpg'],
            ['code' => 'PROJ-02', 'name' => 'Projector 02', 'category' => 'Electronics', 'capacity' => 1, 'approval_type' => 'NONE', 'image' => 'images/resources/projector.jpg'],
            ['code' => 'CAM-01', 'name' => 'DSLR Camera', 'category' => 'Media Equipment', 'capacity' => 1, 'approval_type' => 'MANAGER', 'image' => 'images/resources/dslr_camera.jpg'],
            ['code' => 'TRIP-01', 'name' => 'Camera Tripod', 'category' => 'Media Equipment', 'capacity' => 1, 'approval_type' => 'NONE', 'image' => 'images/resources/camera_tripod.jpg'],
            ['code' => 'LAP-01', 'name' => 'Laptop 01', 'category' => 'IT Equipment', 'capacity' => 1, 'approval_type' => 'NONE', 'image' => 'images/resources/laptop.jpg'],
            ['code' => 'LAP-02', 'name' => 'Laptop 02', 'category' => 'IT Equipment', 'capacity' => 1, 'approval_type' => 'NONE', 'image' => 'images/resources/laptop.jpg'],
            ['code' => 'CAR-01', 'name' => 'Company Car', 'category' => 'Vehicle', 'capacity' => 5, 'approval_type' => 'MANAGER', 'image' => 'images/resources/company_car.jpg'],
            ['code' => 'VAN-01', 'name' => 'Company Van', 'category' => 'Vehicle', 'capacity' => 8, 'approval_type' => 'MANAGER', 'image' => 'images/resources/vehicle.jpg'],
            ['code' => '3D-01', 'name' => '3D Printer', 'category' => 'Specialized Equipment', 'capacity' => 1, 'approval_type' => 'MANAGER', 'image' => 'images/resources/3d_printer.jpg'],
            ['code' => 'AUD-01', 'name' => 'Auditorium', 'category' => 'Event Space', 'capacity' => 250, 'approval_type' => 'MANAGER', 'image' => 'images/resources/auditorium.jpg'],
        ];

        foreach ($resources as $res) {
            Resource::updateOrCreate(
                ['code' => $res['code']],
                [
                    'name' => $res['name'],
                    'category_id' => $cats[$res['category']]->id,
                    'manager_id' => $manager->id,
                    'location' => 'HQ',
                    'description' => $res['name'] . ' resource.',
                    'capacity' => $res['capacity'],
                    'status' => 'AVAILABLE',
                    'approval_type' => $res['approval_type'],
                    'price' => rand(50, 500),
                    'image_path' => $res['image']
                ]
            );
        }
    }
}
