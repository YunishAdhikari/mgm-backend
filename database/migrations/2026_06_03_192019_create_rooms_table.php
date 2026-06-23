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
        Schema::create('rooms', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hotel_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('room_type_id')
        ->nullable()
        ->constrained('room_types')
        ->nullOnDelete();

    $table->string('room_number');
    $table->string('floor')->nullable();

    $table->integer('max_occupancy')->default(2);

    $table->enum('status', [
        'available',
        'occupied',
        'dirty',
        'inspected',
        'out_of_order',
        'out_of_service',
    ])->default('available');

    $table->enum('housekeeping_status', [
        'clean',
        'dirty',
        'in_progress',
        'inspection_pending',
        'inspected',
        'rejected',
        'dnd',
        'refused_service',
    ])->default('clean');

    $table->enum('maintenance_status', [
        'clear',
        'maintenance_required',
        'out_of_order',
        'out_of_service',
    ])->default('clear');

    $table->boolean('is_active')->default(true);
    $table->text('notes')->nullable();

    $table->timestamps();

    $table->unique(['hotel_id', 'room_number']);
    $table->index(['hotel_id', 'floor']);
    $table->index(['hotel_id', 'status']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
