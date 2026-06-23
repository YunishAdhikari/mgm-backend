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
        Schema::create('restaurant_booking_settings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('restaurant_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('booking_type', [
        'afternoon_tea',
        'dinner',
    ]);

    $table->time('opening_time');
    $table->time('closing_time');

    $table->integer('slot_duration_minutes')->default(30);
    $table->integer('slot_interval_minutes');

    $table->integer('max_pax_per_slot');

    $table->boolean('allow_overbooking')->default(true);
    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['restaurant_id', 'booking_type']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_booking_settings');
    }
};