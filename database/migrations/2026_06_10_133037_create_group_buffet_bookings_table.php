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
    Schema::create('group_buffet_bookings', function (Blueprint $table) {
        $table->id();

        $table->string('group_name');
        $table->string('agent_name')->nullable();

        $table->date('buffet_date');
        $table->time('buffet_time');

        $table->integer('pax');

        $table->enum('meal_type', [
            'breakfast',
            'lunch',
            'dinner',
            'afternoon_tea',
            'private_event',
        ]);

        $table->decimal('price_per_person', 8, 2)->nullable();
        $table->decimal('total_amount', 10, 2)->nullable();

        $table->enum('payment_status', [
            'pending',
            'paid',
            'city_ledger',
            'complimentary',
        ])->default('pending');

        $table->enum('status', [
            'confirmed',
            'served',
            'completed',
            'cancelled',
        ])->default('confirmed');

        $table->text('notes')->nullable();

        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_buffet_bookings');
    }
};
