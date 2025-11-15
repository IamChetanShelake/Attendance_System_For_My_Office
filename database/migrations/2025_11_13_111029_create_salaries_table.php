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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('salary_amount', 12, 2);
            $table->string('pan_number')->nullable();
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('ifsc_code');
            $table->string('branch_name')->nullable();
            $table->date('effective_date');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->decimal('hra', 10, 2)->nullable();
            $table->decimal('conveyance', 10, 2)->nullable();
            $table->decimal('medical_allowance', 10, 2)->nullable();
            $table->decimal('lta', 10, 2)->nullable();
            $table->decimal('special_allowance', 10, 2)->nullable();
            $table->decimal('provident_fund', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
