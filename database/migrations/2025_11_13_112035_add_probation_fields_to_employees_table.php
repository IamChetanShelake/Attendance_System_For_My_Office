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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('probation_start_date')->nullable()->after('onrole_date');
            $table->date('probation_end_date')->nullable()->after('probation_start_date');
            $table->enum('probation_status', ['active', 'completed', 'extended', 'failed'])->nullable()->after('probation_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['probation_start_date', 'probation_end_date', 'probation_status']);
        });
    }
};
