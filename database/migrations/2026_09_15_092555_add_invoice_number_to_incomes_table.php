<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('incomes') && !Schema::hasColumn('incomes', 'invoice_number')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->string('invoice_number', 50)->nullable()->unique()->after('income_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('incomes') && Schema::hasColumn('incomes', 'invoice_number')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->dropColumn('invoice_number');
            });
        }
    }
};