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
        Schema::create('room_types', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hotel_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('name');
    $table->string('code')->nullable();
    $table->integer('default_pax')->default(2);
    $table->text('description')->nullable();
    $table->string('colour')->nullable();

    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['hotel_id', 'name']);
    $table->unique(['hotel_id', 'code']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
