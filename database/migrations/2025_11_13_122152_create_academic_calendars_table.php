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
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->enum('type', ['holiday', 'celebration', 'event', 'deadline', 'meeting'])->default('event');
            $table->string('image')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['yearly', 'monthly', 'weekly'])->nullable();
            $table->json('recurrence_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
