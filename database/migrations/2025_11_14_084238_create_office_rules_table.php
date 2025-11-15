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
        Schema::create('office_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_type')->index(); // office_timing, late_mark, half_day, weekend_policy, consecutive_leave, holiday_consecutive
            $table->string('rule_category')->nullable(); // attendance, leave, holiday, etc.
            $table->string('rule_name');
            $table->text('rule_description')->nullable();
            $table->json('rule_settings'); // Store specific configurations
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // For rule ordering
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();

            // Indexes for performance
            $table->index(['rule_type', 'is_active']);
            $table->index(['effective_from', 'effective_to']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_rules');
    }
};
