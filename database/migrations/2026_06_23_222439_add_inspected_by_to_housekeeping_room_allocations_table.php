<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housekeeping_room_allocations', function (Blueprint $table) {

            $table->foreignId('inspected_by')
                ->nullable()
                ->after('inspected_at')
                ->constrained('users')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('housekeeping_room_allocations', function (Blueprint $table) {

            $table->dropConstrainedForeignId('inspected_by');

        });
    }
};