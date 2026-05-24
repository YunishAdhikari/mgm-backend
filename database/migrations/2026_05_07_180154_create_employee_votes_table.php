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
    Schema::create('employee_votes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('poll_id')
            ->constrained('employee_vote_polls')
            ->cascadeOnDelete();

        $table->foreignId('voter_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('employee_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->integer('points');

        $table->text('reason')->nullable();

        $table->timestamps();

        $table->unique(['poll_id', 'voter_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_votes');
    }
};
