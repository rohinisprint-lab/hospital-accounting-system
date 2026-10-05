<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('incomes')) {
            Schema::create('incomes', function (Blueprint $table) {
                $table->id('income_id');
                $table->string('voucher_number')->unique();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('income_head_id');
                $table->decimal('amount', 12, 2);
                $table->date('entry_date');
                $table->string('payment_mode');
                $table->string('received_from')->nullable();
                $table->string('patient_id')->nullable();
                $table->string('reference_number')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id('expense_id');
                $table->string('voucher_number')->unique();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('expense_head_id');
                $table->decimal('amount', 12, 2);
                $table->date('entry_date');
                $table->string('payment_mode');
                $table->string('paid_to')->nullable();
                $table->string('bill_number')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('incomes');
    }
};