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
        Schema::create('buffet_menu_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buffet_menu_id')->constrained()->cascadeOnDelete();
    $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffet_menu_items');
    }
};
