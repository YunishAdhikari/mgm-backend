<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_operations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('hotel_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('operation_date');

            $table->enum('snapshot', [
                'AM',
                'PM',
            ]);

            $table->unsignedInteger('arrivals')->default(0);

            $table->unsignedInteger('departures')->default(0);

            $table->unsignedInteger('stayovers')->default(0);

            $table->unsignedInteger('ooo_rooms')->default(0);

            $table->unsignedInteger('ooi_rooms')->default(0);

            $table->unsignedInteger('vip_arrivals')->default(0);

            $table->unsignedInteger('group_arrivals')->default(0);

            $table->unsignedInteger('group_departures')->default(0);

            $table->unsignedInteger('expected_breakfast')->default(0);

            $table->unsignedInteger('expected_dinner')->default(0);

            $table->text('notes')->nullable();
            $table->boolean('is_finalised')->default(false);


            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'hotel_id',
                'operation_date',
                'snapshot',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_operations');
    }
};