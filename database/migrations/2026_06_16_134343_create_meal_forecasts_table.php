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
    Schema::create('meal_forecasts', function (Blueprint $table) {
        $table->id();
        $table->date('forecast_date')->unique();
        $table->integer('total_breakfast')->default(0);
        $table->integer('total_dinner')->default(0);
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_forecasts');
    }
};
