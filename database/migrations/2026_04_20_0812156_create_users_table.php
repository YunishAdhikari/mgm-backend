<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            //Hotel

            $table->foreignId('hotel_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            //personal info

            $table->string('name');

            $table->string('email')->unique();

            $table->string('phone')->nullable();

            $table->string('employee_code')->nullable()->unique();

            $table->string('job_title')->nullable();

            $table->string('image')->nullable();

            //Roles and department

            $table->foreignId('role_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            //Account

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->rememberToken();

            //Audit

            $table->timestamp('last_login_at')->nullable();

            $table->string('last_login_ip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};