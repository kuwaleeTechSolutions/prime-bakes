<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('adjustment_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('variant_id')->nullable();
            $table->double('qty');
            $table->enum('action', ['+', '-']); // matches source data exactly
            $table->timestamps();

            $table->foreign('adjustment_id')->references('id')->on('adjustments')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_adjustments');
    }
};
