<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Booking Cancellation Cutoff
    |--------------------------------------------------------------------------
    |
    | This value determines how many hours before a booking's start time it 
    | can still be cancelled by the user if it is already approved.
    |
    */

    'cancellation_cutoff_hours' => env('BOOKING_CANCELLATION_CUTOFF_HOURS', 24),

];
