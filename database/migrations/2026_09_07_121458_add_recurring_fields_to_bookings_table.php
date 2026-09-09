<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('recurring_pattern', ['NONE', 'DAILY', 'WEEKLY', 'WEEKDAYS'])->default('NONE')->after('status');
            $table->foreignId('parent_booking_id')->nullable()->constrained('bookings')->onDelete('cascade')->after('recurring_pattern');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['parent_booking_id']);
            $table->dropColumn(['recurring_pattern', 'parent_booking_id']);
        });
    }
};
