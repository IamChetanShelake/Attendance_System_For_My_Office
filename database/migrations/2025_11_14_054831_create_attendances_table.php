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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->timestamp('punch_in_time')->nullable();
            $table->timestamp('punch_out_time')->nullable();
            $table->string('status')->default('present'); // present/absent/half-day
            $table->decimal('worked_hours', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['employee_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
