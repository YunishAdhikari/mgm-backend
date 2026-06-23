<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_floor_objects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('restaurant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'wall',
                'door',
                'window',
                'bar',
                'buffet',
                'cashier',
                'toilet',
                'plant',
                'sofa',
                'note',
            ]);

            $table->string('label')->nullable();

            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);

            $table->integer('width')->default(120);
            $table->integer('height')->default(80);

            $table->integer('rotation')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['restaurant_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_floor_objects');
    }
};