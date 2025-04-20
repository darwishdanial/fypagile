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
        Schema::table('students_psm2', function (Blueprint $table) {
            $table->unsignedBigInteger('panelId_ai')->nullable()->after('panel2Id'); // adjust 'after' as needed
            $table->unsignedBigInteger('panel2Id_ai')->nullable()->after('panelId_ai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students_psm2', function (Blueprint $table) {
            $table->dropColumn(['panelId_ai', 'panel2Id_ai']);
        });
    }
};
