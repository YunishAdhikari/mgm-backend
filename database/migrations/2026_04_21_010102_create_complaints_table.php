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

            /*
            |--------------------------------------------------------------------------
            | Hotel
            |--------------------------------------------------------------------------
            */
            $table->foreignId('hotel_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Guest Details
            |--------------------------------------------------------------------------
            */
            $table->string('guest_name')->nullable();

            $table->string('email')->nullable();

            $table->string('phone')->nullable();

            $table->string('room_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Complaint Details
            |--------------------------------------------------------------------------
            */

            $table->enum('type', [
                'complaint',
                'feedback',
            ])->default('complaint');

            $table->string('category')->nullable();

            $table->string('title');

            $table->longText('description');

            $table->string('image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Priority & Status
            |--------------------------------------------------------------------------
            */

            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent',
            ])->default('medium');

            $table->enum('status', [
                'pending',
                'in_progress',
                'resolved',
                'closed',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('handled_at')->nullable();

            $table->text('internal_note')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('hotel_id');
            $table->index('status');
            $table->index('priority');
            $table->index('room_number');
            $table->index('created_by');
            $table->index('handled_by');
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