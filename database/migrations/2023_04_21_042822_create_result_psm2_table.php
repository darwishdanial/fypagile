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
        Schema::create('result_psm2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->nullable()->references('id')->on('students_psm2'); 
            $table->string("type")->nullable();
            $table->foreignId('typeId')->nullable()->references('id')->on('users'); 
            $table->decimal("supervision", 10, 2)->nullable();
            $table->decimal("progressreport", 10, 2)->nullable();
            $table->decimal("projectprogress", 10, 2)->nullable();
            $table->decimal("projectprogress2", 10, 2)->nullable();
            $table->decimal("presentation", 10, 2)->nullable();
            $table->decimal("shortpaper", 10, 2)->nullable();
            $table->decimal("finalreport", 10, 2)->nullable();
            $table->decimal("system", 10, 2)->nullable();
            $table->decimal("ethics", 10, 2)->nullable();
            $table->decimal("totalshared", 10, 2)->nullable();
            $table->decimal("total", 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('result_psm2');
    }
};
