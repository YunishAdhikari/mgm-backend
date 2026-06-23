<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rota_shifts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hotel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->date('shift_date');

            $table->enum('shift_type', [
                'morning',
                'evening',
                'night',
                'split',
                'day_off',
                'holiday',
                'sick',
            ])->default('morning');

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->integer('break_minutes')->default(0);

            $table->enum('status', [
                'draft',
                'published',
            ])->default('draft');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['hotel_id', 'shift_date']);
            $table->index(['department_id', 'shift_date']);
            $table->unique(['hotel_id', 'user_id', 'shift_date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rota_shifts');
    }
};