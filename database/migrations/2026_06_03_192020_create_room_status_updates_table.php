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
        Schema::create('room_status_updates', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hotel_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('room_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->date('status_date');

    $table->enum('status', [
        'departure',
        'stay',
        'room_move',
        'carry_forward',
        'OOO',
        'OOI',
    ]);

    $table->text('notes')->nullable();

    $table->foreignId('updated_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();

    $table->unique(['room_id', 'status_date']);
    $table->index(['hotel_id', 'status_date']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_status_updates');
    }
};
