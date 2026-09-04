<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('resource_categories')->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->integer('capacity')->default(1);
            $table->enum('status', ['AVAILABLE', 'UNAVAILABLE', 'MAINTENANCE', 'INACTIVE'])->default('AVAILABLE');
            $table->enum('approval_type', ['NONE', 'MANAGER', 'ADMIN'])->default('NONE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
