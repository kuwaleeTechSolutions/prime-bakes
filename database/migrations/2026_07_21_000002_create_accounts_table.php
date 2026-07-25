<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Just enough to support POS checkout (picking a payment account). Full
    // accounting (money transfers, expenses, gift cards) is a separate module —
    // this table is the shared dependency both need, so it's built here first.
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_no');
            $table->string('name');
            $table->double('initial_balance')->default(0);
            $table->double('total_balance')->default(0);
            $table->text('note')->nullable();
            $table->boolean('is_default')->default(0);
            $table->boolean('is_upi')->default(0);
            $table->boolean('is_card')->default(0);
            $table->boolean('is_cheque')->default(0);
            $table->boolean('is_gift')->default(0);
            $table->boolean('is_deposit')->default(0);
            $table->boolean('is_points')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
