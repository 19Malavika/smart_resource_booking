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
            $table->text('rejection_reason')->nullable()->after('status');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn([
                'rejection_reason',
                'rejected_by',
                'rejected_at',
                'cancellation_reason',
                'cancelled_by'
            ]);
        });
    }
};
