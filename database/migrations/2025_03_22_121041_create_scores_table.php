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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rubric_id')
                ->constrained('rubrics')
                ->onDelete('cascade');
            $table->foreignId('student_psm1_id')
                ->nullable() 
                ->constrained('students_psm1')
                ->onDelete('cascade');
            $table->foreignId('student_psm2_id')
                ->nullable() 
                ->constrained('students_psm2')
                ->onDelete('cascade');
            $table->foreignId('panel_id')
                ->constrained('users');
            $table->string('panel_name');
            $table->decimal('mark');
            $table->longText('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
