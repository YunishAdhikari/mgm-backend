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
        Schema::create('restaurant_bookings', function (Blueprint $table) {
    $table->id();

    $table->foreignId('restaurant_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->enum('booking_type', [
        'afternoon_tea',
        'dinner',
    ]);

    $table->foreignId('restaurant_table_id')
        ->nullable()
        ->constrained('restaurant_tables')
        ->nullOnDelete();

    $table->string('guest_name');
    $table->string('guest_phone')->nullable();
    $table->string('guest_email')->nullable();

    $table->date('booking_date');
    $table->time('slot_start_time');
    $table->time('slot_end_time');

    $table->string('voucher_code')->nullable();
    $table->decimal('voucher_amount', 8, 2)->nullable();
    $table->text('voucher_note')->nullable();

    $table->integer('pax');

    $table->boolean('is_overbooking')->default(false);

    $table->enum('status', [
        'confirmed',
        'seated',
        'completed',
        'cancelled',
        'no_show',
    ])->default('confirmed');

    $table->text('special_request')->nullable();

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();

    $table->index(['restaurant_id', 'booking_date']);
    $table->index(['restaurant_id', 'booking_type']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_bookings');
    }
};