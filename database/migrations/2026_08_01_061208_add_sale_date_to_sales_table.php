<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Separate from created_at (which always reflects when the row was
            // technically inserted) — this is the business-meaningful sale date,
            // editable at POS, defaulting to today but backdateable if needed.
            $table->date('sale_date')->nullable()->after('warehouse_id');
        });

        // Backfill existing rows so nothing shows a blank date after this migration.
        // DATE() works identically on both MySQL and PostgreSQL.
        DB::table('sales')->whereNull('sale_date')->update([
            'sale_date' => DB::raw('DATE(created_at)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('sale_date');
        });
    }
};