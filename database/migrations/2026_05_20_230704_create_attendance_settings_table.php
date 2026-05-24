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
        Schema::create('attendance_settings', function (Blueprint $table) {
    $table->id();

    $table->string('hotel_wifi_ip')->nullable();

    $table->decimal('hotel_latitude', 10, 7)->nullable();
    $table->decimal('hotel_longitude', 10, 7)->nullable();

    $table->integer('allowed_radius_meters')->default(100);

    $table->boolean('is_ip_check_enabled')->default(true);
    $table->boolean('is_location_check_enabled')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
