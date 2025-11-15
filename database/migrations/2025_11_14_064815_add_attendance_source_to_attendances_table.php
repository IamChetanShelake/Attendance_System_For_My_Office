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
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('punch_in_source', ['qr', 'manual', 'auto'])->nullable()->after('worked_hours');
            $table->enum('punch_out_source', ['qr', 'manual', 'auto'])->nullable()->after('punch_in_source');
            $table->string('attendance_type')->default('office')->after('punch_out_source'); // office, wfh, hybrid
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['punch_in_source', 'punch_out_source', 'attendance_type']);
        });
    }
};
