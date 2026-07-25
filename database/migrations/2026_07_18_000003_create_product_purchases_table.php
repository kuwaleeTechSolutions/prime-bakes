<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('product_batch_id')->nullable();
            $table->unsignedInteger('variant_id')->nullable();
            $table->text('imei_number')->nullable();

            $table->double('qty');           // quantity ordered
            $table->double('recieved');      // quantity actually received (source spelling kept for consistency)
            $table->unsignedInteger('purchase_unit_id');
            $table->double('net_unit_cost'); // cost per purchase_unit, after line discount
            $table->double('discount')->default(0);
            $table->double('tax_rate')->default(0);
            $table->double('tax')->default(0);
            $table->double('total');
            $table->timestamps();

            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('purchase_unit_id')->references('id')->on('units');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};
