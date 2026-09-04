<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained('resources')->onDelete('cascade');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['SCHEDULED', 'ACTIVE', 'COMPLETED', 'CANCELLED'])->default('SCHEDULED');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_maintenances');
    }
};
