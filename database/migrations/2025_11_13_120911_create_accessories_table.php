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
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('accessory_name');
            $table->text('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('model_number')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->date('allocation_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['allocated', 'returned', 'lost', 'damaged'])->default('allocated');
            $table->text('condition_notes')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};
