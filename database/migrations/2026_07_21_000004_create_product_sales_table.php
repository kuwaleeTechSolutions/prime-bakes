<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_batch_id')->nullable();
            $table->unsignedInteger('variant_id')->nullable();
            $table->text('imei_number')->nullable();
            $table->double('qty');
            $table->unsignedInteger('sale_unit_id');
            $table->double('net_unit_price');
            $table->double('discount')->default(0);
            $table->double('tax_rate')->default(0);
            $table->double('tax')->default(0);
            $table->double('total');
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('sale_unit_id')->references('id')->on('units');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sales');
    }
};
