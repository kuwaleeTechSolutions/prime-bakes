<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_title');
            $table->string('site_logo')->nullable();
            $table->boolean('is_rtl')->default(0);
            $table->string('currency');       // currency code, e.g. 'INR'
            $table->string('currency_position')->default('prefix'); // 'prefix' | 'suffix'
            $table->string('staff_access')->default('all'); // 'all' | 'own_warehouse' — scopes what a non-admin can see
            $table->string('date_format')->default('d-m-Y');
            $table->string('invoice_format')->default('standard');
            $table->string('theme')->default('default.css');
            $table->boolean('cash_register')->default(1); // whether POS enforces the open-register gate
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
