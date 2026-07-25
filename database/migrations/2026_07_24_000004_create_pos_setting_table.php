<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_id');   // default walk-in customer
            $table->unsignedInteger('warehouse_id');  // default POS warehouse
            $table->unsignedInteger('biller_id');     // default biller
            $table->integer('product_number')->default(50); // how many products to show per POS page
            $table->boolean('keybord_active')->default(0);  // on-screen keyboard toggle (source spelling kept)
            $table->string('stripe_public_key')->nullable();
            $table->string('stripe_secret_key')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('biller_id')->references('id')->on('billers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_setting');
    }
};
