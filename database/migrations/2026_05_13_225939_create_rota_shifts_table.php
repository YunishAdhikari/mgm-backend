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
    Schema::create('rota_shifts', function (Blueprint $table) {
        $table->id();

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

        $table->timestamps();
    });
}
    // public function up(): void
    // {
    //     Schema::create('rota_shifts', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rota_shifts');
    }
};
