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
   Schema::create('meal_forecast_groups', function (Blueprint $table) {
    $table->id();

    $table->foreignId('meal_forecast_id')
        ->constrained('meal_forecasts')
        ->cascadeOnDelete();

    $table->foreignId('forecast_group_id')
        ->constrained('forecast_groups')
        ->cascadeOnDelete();

    // Group Details
    $table->enum('package_type', [
        'room_only',
        'bb',
        'dbb',
        'dinner_only'
    ])->default('dbb');

    $table->integer('pax')->default(0);

    $table->date('check_in_date');
    $table->date('check_out_date');

    $table->text('notes')->nullable();

    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_forecast_groups');
    }
};
