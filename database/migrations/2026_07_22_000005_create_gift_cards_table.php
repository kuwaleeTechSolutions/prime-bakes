<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_no')->unique();
            $table->double('amount');   // current usable balance
            $table->double('expense')->default(0); // total redeemed to date
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('user_id')->nullable(); // last user who transacted on this card
            $table->date('expired_date')->nullable();
            $table->unsignedInteger('created_by');
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_cards');
    }
};
