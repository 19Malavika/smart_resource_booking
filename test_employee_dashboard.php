<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('role', 'employee')->first();
if (!$user) {
    echo "No employee user found.\n";
    exit;
}

try {
    $availableResourcesCount = App\Models\Resource::where('status', 'AVAILABLE')->count();
    $availableResourcesList = App\Models\Resource::where('status', 'AVAILABLE')->latest()->limit(5)->get();
    
    $myBookings = App\Models\Booking::with('resource')->where('user_id', $user->id)->latest()->get();
    
    $pendingCount = $myBookings->where('status', 'PENDING')->count();
    $approvedCount = $myBookings->where('status', 'APPROVED')->count();
    $rejectedCount = $myBookings->where('status', 'REJECTED')->count();
    
    $upcomingBookings = App\Models\Booking::with('resource')
        ->where('user_id', $user->id)
        ->where('status', 'APPROVED')
        ->where('start_datetime', '>=', now())
        ->orderBy('start_datetime')
        ->limit(6)
        ->get();
        
    $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
    
    $historyData = App\Models\Booking::where('user_id', $user->id)
        ->where('start_datetime', '>=', $sixMonthsAgo)
        ->get()
        ->groupBy(function ($booking) {
            return $booking->start_datetime->format('M');
        })
        ->map(function ($group) {
            return $group->count();
        });
        
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
