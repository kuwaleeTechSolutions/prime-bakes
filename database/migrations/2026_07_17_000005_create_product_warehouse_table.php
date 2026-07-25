<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The single source of truth for stock levels — every sale, purchase, transfer,
    // damage, and adjustment ultimately reads/writes rows here (see StockService
    // in the README). product_id is a string in the source dump (legacy quirk);
    // kept as-is rather than "fixed", to stay compatible with any existing data import.
    public function up(): void
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->unsignedInteger('product_batch_id')->nullable();
            $table->unsignedInteger('variant_id')->nullable();
            $table->text('imei_number')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->double('qty');
            $table->double('price')->nullable(); // per-warehouse price override, if used
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->index(['product_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};
