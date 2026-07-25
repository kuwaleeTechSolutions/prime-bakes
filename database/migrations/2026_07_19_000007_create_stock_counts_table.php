<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no')->unique();
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('category_id')->nullable(); // source stores this as varchar; kept nullable int here since it's a real FK target
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('type'); // 'full' | 'partial' (by category/brand filter)
            $table->string('initial_file')->nullable(); // CSV snapshot of expected qty at count start
            $table->string('final_file')->nullable();   // CSV of actual counted qty
            $table->text('note')->nullable();
            $table->boolean('is_adjusted')->default(0); // true once a matching `adjustments` record has reconciled the difference
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_counts');
    }
};
