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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 3)->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->date('dob');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('department');
            $table->enum('type', ['intern', 'onrole']);
            $table->string('position');
            $table->date('start_date');
            $table->date('onrole_date')->nullable();
            $table->string('photo')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
