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
       Schema::create('attendance_logs', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->date('attendance_date');

    $table->timestamp('clock_in_at')->nullable();
    $table->timestamp('clock_out_at')->nullable();

    $table->string('clock_in_ip')->nullable();
    $table->string('clock_out_ip')->nullable();

    $table->enum('status', ['clocked_in', 'clocked_out'])
        ->default('clocked_in');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
