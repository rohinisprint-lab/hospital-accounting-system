<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Branches Table
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->string('branch_code', 20)->unique();
            $table->string('branch_name', 100);
            $table->text('branch_address')->nullable();
            $table->string('manager_name', 100)->nullable();
            $table->string('mobile_number', 15)->nullable();
            $table->date('opening_date')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });

        // Add role & branch_id to existing users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Super Admin', 'Accounts', 'Branch Manager'])->default('Branch Manager')->after('email');
            $table->unsignedBigInteger('branch_id')->nullable()->after('role');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('branch_id');
            $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('set null');
        });

        // 2. Income Heads Table
        Schema::create('income_heads', function (Blueprint $table) {
            $table->id('head_id');
            $table->string('head_name', 100)->unique();
            $table->timestamps();
        });

        // 3. Expense Heads Table
        Schema::create('expense_heads', function (Blueprint $table) {
            $table->id('head_id');
            $table->string('head_name', 100)->unique();
            $table->timestamps();
        });

        // 4. Income Entries Table
        Schema::create('income_entries', function (Blueprint $table) {
            $table->id('income_id');
            $table->date('income_date');
            $table->foreignId('branch_id')->constrained('branches', 'branch_id');
            $table->foreignId('head_id')->constrained('income_heads', 'head_id');
            $table->string('bill_number', 50);
            $table->string('patient_name', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_mode', ['Cash', 'UPI', 'Card', 'Bank Transfer', 'Cheque', 'Insurance']);
            $table->string('reference_no', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Verified', 'Cancelled'])->default('Draft');
            $table->foreignId('entered_by')->constrained('users', 'id');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->foreign('verified_by')->references('id')->on('users');
            $table->timestamps();
        });

        // 5. Expense Permissions Table
        Schema::create('expense_permissions', function (Blueprint $table) {
            $table->id('permission_id');
            $table->string('permission_no', 50)->unique();
            $table->foreignId('branch_id')->constrained('branches', 'branch_id');
            $table->foreignId('head_id')->constrained('expense_heads', 'head_id');
            $table->decimal('approved_amount', 12, 2);
            $table->decimal('single_txn_limit', 12, 2);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->foreignId('approved_by')->constrained('users', 'id');
            $table->text('approval_remarks')->nullable();
            $table->enum('status', ['Active', 'Used', 'Expired', 'Cancelled'])->default('Active');
            $table->timestamps();
        });

        // 6. Expense Entries Table
        Schema::create('expense_entries', function (Blueprint $table) {
            $table->id('expense_id');
            $table->date('expense_date');
            $table->foreignId('branch_id')->constrained('branches', 'branch_id');
            $table->foreignId('head_id')->constrained('expense_heads', 'head_id');
            $table->unsignedBigInteger('permission_id')->nullable();
            $table->foreign('permission_id')->references('permission_id')->on('expense_permissions');
            $table->text('expense_description')->nullable();
            $table->string('vendor_name', 100)->nullable();
            $table->string('bill_number', 50)->nullable();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_mode', ['Cash', 'UPI', 'Bank Transfer', 'Cheque']);
            $table->string('bank_name', 100)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('entered_by')->constrained('users', 'id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->enum('status', ['Draft', 'Submitted', 'Verified', 'Approved', 'Rejected', 'Paid', 'Cancelled'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_entries');
        Schema::dropIfExists('expense_permissions');
        Schema::dropIfExists('income_entries');
        Schema::dropIfExists('expense_heads');
        Schema::dropIfExists('income_heads');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'branch_id', 'status']);
        });
        Schema::dropIfExists('branches');
    }
};
