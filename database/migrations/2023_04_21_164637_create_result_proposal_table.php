<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_proposal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('studentId')->nullable()->references('id')->on('students_psm1'); 
            $table->foreignId('panelProposalId')->nullable()->references('id')->on('users');  
            $table->string("approval");
            $table->string("notes");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_proposal');
    }
};
