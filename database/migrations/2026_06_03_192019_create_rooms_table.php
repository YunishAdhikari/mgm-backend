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

    $table->string('room_number')->unique();
    // $table->string('room_type')->nullable();
    $table->foreignId('room_type_id')
    ->nullable()
    ->constrained('room_types')
    ->nullOnDelete();
    $table->string('floor')->nullable();

    $table->integer('max_occupancy')->default(2);

    $table->boolean('is_active')->default(true);

    $table->text('notes')->nullable();

    $table->timestamps();
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
