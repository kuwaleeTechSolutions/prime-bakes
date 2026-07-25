<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // status: same situation as elsewhere — raw integer with no lookup table
    // in the source dump. Using a readable string enum here; see the
    // Purchases module README for the import-mapping note.
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->date('to_date')->nullable(); // multi-day attendance entries, matching the source data's use of a range
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('user_id'); // who recorded this entry
            $table->string('checkin');   // stored as free text (e.g. "9:00am"), matching source format
            $table->string('checkout');
            $table->enum('status', ['present', 'absent', 'late', 'half_day'])->default('present');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
