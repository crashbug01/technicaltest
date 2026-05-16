<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users');

            $table->foreignId('approver_1_id')->constrained('users');
            $table->foreignId('approver_2_id')->constrained('users');

            $table->enum('status', ['pending', 'approved_lvl_1', 'approved_final', 'rejected'])->default('pending');
            $table->dateTime('start_date');
            $table->dateTIme('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
