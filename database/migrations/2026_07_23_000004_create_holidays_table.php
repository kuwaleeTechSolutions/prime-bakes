<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Named `holidays` in the source schema but functions as leave requests —
    // user_id here identifies the employee's linked user account, matching
    // the source column name exactly even though "employee_id" would read
    // more clearly (kept faithful rather than renamed).
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('note')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
