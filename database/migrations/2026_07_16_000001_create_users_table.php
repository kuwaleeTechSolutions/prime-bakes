<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Matches `users` exactly as defined in h24x7in_pb1.sql — no email_verified_at
    // column exists in the source schema, so email verification is not used here.
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('phone');
            $table->string('company_name')->nullable();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('biller_id')->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('biller_id')->references('id')->on('billers')->nullOnDelete();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
