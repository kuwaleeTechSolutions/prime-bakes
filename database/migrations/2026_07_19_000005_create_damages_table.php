<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique();
            $table->unsignedInteger('user_id');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->unsignedInteger('from_warehouse_id')->nullable();
            $table->unsignedInteger('to_warehouse_id')->nullable(); // set when damaged stock is moved to a disposal/quarantine warehouse rather than removed outright
            $table->integer('item');
            $table->double('total_qty');
            $table->double('total_tax')->default(0);
            $table->double('total_cost')->default(0);
            $table->double('disposal_cost')->nullable();
            $table->double('grand_total');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('from_warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->foreign('to_warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damages');
    }
};
