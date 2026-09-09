<?php
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$resources = App\Models\Resource::all();

foreach ($resources as $resource) {
    $name = strtolower($resource->name);
    $price = 0;
    
    if (str_contains($name, 'suv')) {
        $price = 4500; // ~4500 INR/day for Executive SUV
    } elseif (str_contains($name, 'car')) {
        $price = 2500; // ~2500 INR/day for Company Car
    } elseif (str_contains($name, 'room') || str_contains($name, 'hall') || str_contains($name, 'auditorium')) {
        if (str_contains($name, 'auditorium')) $price = 15000;
        elseif (str_contains($name, 'seminar')) $price = 8000;
        else $price = 2000;
    } elseif (str_contains($name, 'macbook') || str_contains($name, 'laptop')) {
        $price = 800;
    } elseif (str_contains($name, 'ipad')) {
        $price = 500;
    } elseif (str_contains($name, 'projector')) {
        $price = 1000;
    } elseif (str_contains($name, 'camera')) {
        $price = 1200;
    } elseif (str_contains($name, 'tripod') || str_contains($name, 'speaker')) {
        $price = 200;
    } elseif (str_contains($name, 'printer')) {
        $price = 1500;
    } else {
        $price = 1000;
    }
    
    $resource->price = $price;
    $resource->save();
}

echo "Prices updated successfully to realistic INR values.\n";
