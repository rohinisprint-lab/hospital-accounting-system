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
    Schema::create('cash_closings', function (Blueprint $table) {
        $table->id('closing_id');
        $table->foreignId('branch_id')->nullable()->constrained('branches', 'branch_id')->nullOnDelete();
        $table->date('closing_date');
        $table->decimal('opening_float', 12, 2)->default(0.00);
        $table->decimal('system_cash_in', 12, 2)->default(0.00);
        $table->decimal('system_cash_out', 12, 2)->default(0.00);
        $table->decimal('expected_cash', 12, 2)->default(0.00);
        $table->decimal('counted_physical_cash', 12, 2)->default(0.00);
        $table->decimal('discrepancy', 12, 2)->default(0.00); // counted - expected
        $table->string('closed_by')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
    }
};
