<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique();
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('user_id'); // who processed the payment
            $table->double('amount');
            $table->string('paying_method');
            $table->text('note')->nullable();
            $table->string('month'); // e.g. "July" — matches source data's plain-text month, not a date
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
