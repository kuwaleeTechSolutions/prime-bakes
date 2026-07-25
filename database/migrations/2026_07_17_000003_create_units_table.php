<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit_code');
            $table->string('unit_name');
            $table->string('base_unit', 11)->default('0'); // '' / base unit id as string, per source data
            $table->string('operator')->nullable();        // '*' or '/', how this unit relates to the base unit
            $table->double('operation_value')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
