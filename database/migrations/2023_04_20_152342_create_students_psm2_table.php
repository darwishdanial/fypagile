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
        Schema::create('students_psm2', function (Blueprint $table) {
            $table->id();
            $table->string("course");
            $table->string("matric");
            $table->string("name");
            $table->string("title");
            $table->string('project_area'); 
            $table->string('project_area_ai');
            $table->string('project_type'); 
            $table->string("email");
            $table->string("phone");
            $table->string("cohort");
            $table->string("sessionpsm");
            $table->foreignId('supervisorId')->nullable()->references('id')->on('users'); 
            $table->foreignId('panelId')->nullable()->references('id')->on('users'); 
            $table->foreignId('panel2Id')->nullable()->references('id')->on('users'); 
            $table->foreignId('panelId_ai')->nullable()->references('id')->on('users'); 
            $table->foreignId('panel2Id_ai')->nullable()->references('id')->on('users');
            $table->foreignId('svReq')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_psm2');
    }
};
