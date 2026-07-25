<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     * sale_status/payment_status: same situation as purchases.status — raw
     * integers with no lookup table in the source dump. Using readable string
     * enums here for a fresh build; see the purchases module README for the
     * import-mapping note if you're bringing in the original data.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('cash_register_id')->nullable();
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('biller_id')->nullable();

            $table->integer('item');
            $table->double('total_qty');
            $table->double('total_discount')->default(0);
            $table->double('total_tax')->default(0);
            $table->double('total_price');
            $table->double('grand_total');

            $table->double('order_tax_rate')->nullable();
            $table->double('order_tax')->nullable();
            $table->string('order_discount_type')->nullable(); // 'Flat' | 'Percentage'
            $table->double('order_discount_value')->nullable();
            $table->double('order_discount')->nullable();

            $table->unsignedInteger('coupon_id')->nullable();
            $table->double('coupon_discount')->nullable();
            $table->double('shipping_cost')->nullable();

            $table->enum('sale_status', ['pending', 'hold', 'completed'])->default('completed');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            $table->string('document')->nullable();
            $table->double('paid_amount')->default(0);
            $table->text('sale_note')->nullable();
            $table->text('staff_note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->nullOnDelete();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('biller_id')->references('id')->on('billers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
