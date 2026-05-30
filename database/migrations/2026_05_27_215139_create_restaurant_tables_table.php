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
    Schema::create('restaurant_tables', function (Blueprint $table) {
        $table->id();

        $table->string('table_name');

        $table->integer('capacity');

        $table->enum('table_shape', [
                'round',
                'square',
                'horizontal',
                'vertical',
                'banquet'
            ])->default('square');

        $table->enum('status', [
            'available',
            'reserved',
            'occupied',
            'out_of_service'
        ])->default('available');

        // floor plan position
        $table->integer('position_x')
            ->default(0);

        $table->integer('position_y')
            ->default(0);

        $table->boolean('is_active')
            ->default(true);

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};