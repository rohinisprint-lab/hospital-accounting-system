<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expense_heads', function (Blueprint $table) {
            if (!Schema::hasColumn('expense_heads', 'description')) {
                $table->text('description')->nullable()->after('head_name');
            }
            if (!Schema::hasColumn('expense_heads', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_heads', function (Blueprint $table) {
            if (Schema::hasColumn('expense_heads', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('expense_heads', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};