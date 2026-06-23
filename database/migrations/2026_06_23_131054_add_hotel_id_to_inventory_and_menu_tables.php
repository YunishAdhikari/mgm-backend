<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('buffet_menus', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('buffet_menu_items', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('buffet_sales', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('inventory_wastages', function (Blueprint $table) {
            $table->foreignId('hotel_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_wastages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('buffet_sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('buffet_menu_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('buffet_menus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });

        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hotel_id');
        });
    }
};