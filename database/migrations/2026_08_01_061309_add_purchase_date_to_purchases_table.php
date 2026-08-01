<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->date('purchase_date')->nullable()->after('warehouse_id');
        });

        DB::table('purchases')->whereNull('purchase_date')->update([
            'purchase_date' => DB::raw('DATE(created_at)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('purchase_date');
        });
    }
};