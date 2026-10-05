<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add to incomes only if it does not already exist
        if (Schema::hasTable('incomes') && !Schema::hasColumn('incomes', 'created_by')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('branch_id');
            });
        }

        // Add to expenses only if it does not already exist
        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'created_by')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('branch_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('incomes') && Schema::hasColumn('incomes', 'created_by')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }

        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'created_by')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }
    }
};