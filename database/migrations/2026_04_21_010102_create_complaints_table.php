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
    Schema::create('complaints', function (Blueprint $table) {
    $table->id();

    $table->string('guest_name')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();

    $table->string('room_number')->nullable();

    $table->enum('type', ['complaint', 'feedback'])->default('complaint');
    $table->string('category')->nullable();

    $table->string('title');
    $table->text('description');

    $table->string('image')->nullable();

    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending');

    $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->text('internal_note')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
