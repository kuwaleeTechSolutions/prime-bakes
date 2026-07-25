<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('payment_reference')->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('purchase_id')->nullable();
            $table->unsignedInteger('sale_id')->nullable();
            $table->unsignedInteger('cash_register_id')->nullable();
            $table->unsignedInteger('account_id');
            $table->double('amount');
            $table->double('used_points')->nullable();
            $table->double('change')->default(0);
            $table->string('paying_method'); // 'Cash' | 'Card' | 'UPI' | 'Cheque' | 'Gift card' | 'Deposit' | 'Points'
            $table->text('payment_note')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('purchase_id')->references('id')->on('purchases')->cascadeOnDelete();
            $table->foreign('sale_id')->references('id')->on('sales')->cascadeOnDelete();
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->nullOnDelete();
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
