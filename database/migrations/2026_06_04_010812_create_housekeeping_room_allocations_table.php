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
        Schema::create('housekeeping_room_allocations', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hotel_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('room_status_update_id')
        ->constrained('room_status_updates')
        ->cascadeOnDelete();

    $table->foreignId('room_id')
        ->constrained('rooms')
        ->cascadeOnDelete();

    $table->foreignId('assigned_to')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('assigned_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->date('allocation_date');

    $table->enum('cleaning_status', [
        'assigned',
        'in_progress',
        'cleaned',
        'dnd',
        'inspection_pending',
        'inspected',
        'rejected',
        'refused_service',
        'maintenance_required',
    ])->default('assigned');

    $table->integer('estimated_minutes')->default(0);

    $table->text('notes')->nullable();

    $table->timestamp('started_at')->nullable();
    $table->timestamp('cleaned_at')->nullable();
    $table->timestamp('inspected_at')->nullable();

    $table->timestamps();

    $table->index(['hotel_id', 'allocation_date']);
    $table->index(['assigned_to', 'allocation_date']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housekeeping_room_allocations');
    }
};
