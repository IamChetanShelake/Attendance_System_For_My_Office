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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->enum('leave_type', [
                'annual_leave',
                'sick_leave',
                'casual_leave',
                'maternity_leave',
                'paternity_leave',
                'emergency_leave',
                'unpaid_leave',
                'medical_leave',
                'vacation_leave',
                'bereavement_leave',
                'personal_leave'
            ]);
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days_count', 4, 1)->default(0); // Support half days
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->boolean('is_half_day')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->text('admin_notes')->nullable(); // For HR notes about approval/rejection
            $table->boolean('emergency')->default(false); // For emergency leaves
            $table->json('attachments')->nullable(); // URLs to documents if needed
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['employee_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
