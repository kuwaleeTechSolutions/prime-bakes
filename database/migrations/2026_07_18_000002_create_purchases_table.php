<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     * NOTE ON status/payment_status:
     * The source dump stores these as raw integers (`status`, `payment_status`)
     * with no lookup table, so their exact code→meaning mapping isn't recoverable
     * from the schema alone. This migration uses readable string enums instead
     * for a fresh build. If you're importing the original data rather than
     * starting clean, you'll need to map the old integers to these strings
     * yourself (check a handful of rows in your live system against their
     * invoice status in the UI to confirm the mapping before importing).
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique(); // e.g. pr-20260716-103245
            $table->string('invoice_number')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('supplier_id')->nullable();

            $table->integer('item'); // number of distinct product lines
            $table->double('total_qty');
            $table->double('total_discount')->default(0);
            $table->double('total_tax')->default(0);
            $table->double('total_cost')->default(0);
            $table->double('order_tax_rate')->nullable();
            $table->double('order_tax')->nullable();
            $table->double('order_discount')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->double('grand_total');
            $table->double('paid_amount')->default(0);

            $table->enum('status', ['pending', 'ordered', 'received'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->string('document')->nullable(); // uploaded supplier invoice/attachment path
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
