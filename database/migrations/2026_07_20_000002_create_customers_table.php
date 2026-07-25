<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('customer_group_id');
            $table->unsignedInteger('user_id')->nullable(); // set if this customer also has a portal login; unused by default
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number');
            $table->string('tax_no')->nullable();
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->double('points')->default(0);   // loyalty points balance
            $table->double('deposit')->default(0);   // wallet/advance balance
            $table->double('expense')->default(0);   // running total spent, denormalized for quick display
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('customer_group_id')->references('id')->on('customer_groups');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
