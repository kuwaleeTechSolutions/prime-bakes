<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gift_card_recharges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gift_card_id');
            $table->double('amount');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('gift_card_id')->references('id')->on('gift_cards')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gift_card_recharges');
    }
};
