<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expense_heads') && !Schema::hasColumn('expense_heads', 'description')) {
            Schema::table('expense_heads', function (Blueprint $table) {
                $table->string('description', 255)->nullable()->after('head_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expense_heads') && Schema::hasColumn('expense_heads', 'description')) {
            Schema::table('expense_heads', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};