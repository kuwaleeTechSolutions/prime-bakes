<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->double('cash_in_hand');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('warehouse_id');
            $table->boolean('status'); // true = open, false = closed
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};
