<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // status stored as string here (see purchases module README for why) —
    // 'pending' / 'completed'
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique();
            $table->unsignedInteger('user_id');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->unsignedInteger('from_warehouse_id');
            $table->unsignedInteger('to_warehouse_id');
            $table->integer('item');
            $table->double('total_qty');
            $table->double('total_tax')->default(0);
            $table->double('total_cost')->default(0);
            $table->double('shipping_cost')->nullable();
            $table->double('grand_total');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses');
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
