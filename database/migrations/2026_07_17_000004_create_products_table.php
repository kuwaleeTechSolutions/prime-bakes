<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique(); // SKU / scanned barcode value
            $table->string('type')->default('standard'); // standard | combo | digital, per source data
            $table->string('barcode_symbology')->default('CODE128');

            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('purchase_unit_id');
            $table->unsignedInteger('sale_unit_id');

            $table->double('cost');
            $table->double('price');
            $table->double('qty')->nullable();            // legacy aggregate — real stock lives in product_warehouse
            $table->double('alert_quantity')->nullable();  // low-stock threshold, feeds dso_alerts
            $table->double('daily_sale_objective')->nullable();

            $table->boolean('promotion')->nullable();
            $table->string('promotion_price')->nullable();
            $table->string('starting_date')->nullable();
            $table->date('last_date')->nullable();

            $table->unsignedInteger('tax_id')->nullable();
            $table->integer('tax_method')->nullable(); // 1 = inclusive, 2 = exclusive

            $table->longText('image')->nullable();
            $table->string('file')->nullable();
            $table->boolean('is_embeded')->nullable();

            $table->boolean('is_variant')->default(0);
            $table->boolean('is_batch')->default(0);
            $table->boolean('is_diffPrice')->default(0);
            $table->boolean('is_imei')->default(0);
            $table->boolean('featured')->nullable();

            // Denormalized snapshot columns used by the source app's combo/variant product type
            $table->string('product_list')->nullable();
            $table->string('variant_list')->nullable();
            $table->string('qty_list')->nullable();
            $table->string('price_list')->nullable();
            $table->text('product_details')->nullable();
            $table->text('variant_option')->nullable();
            $table->text('variant_value')->nullable();

            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('purchase_unit_id')->references('id')->on('units');
            $table->foreign('sale_unit_id')->references('id')->on('units');
            $table->foreign('tax_id')->references('id')->on('taxes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
