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
        Schema::create('result_totalpsm2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->nullable()->references('id')->on('students_psm2'); 
            $table->decimal('supervision', 10, 2)->nullable();
            $table->decimal('pr', 10, 2)->nullable();
            $table->decimal('pp1', 10, 2)->nullable();
            $table->decimal('pp2', 10, 2)->nullable();
            $table->decimal('shortpaper', 10, 2)->nullable();
            $table->decimal('finalreport', 10, 2)->nullable();
            $table->decimal('system', 10, 2)->nullable();
            $table->decimal('presentation', 10, 2)->nullable();
            $table->decimal('ethics', 10, 2)->nullable();
            $table->decimal('sv', 10, 2)->nullable();
            $table->decimal('panel1', 10, 2)->nullable();
            $table->decimal('panel2', 10, 2)->nullable();
            $table->decimal('coordinator', 10, 2)->nullable();
            $table->decimal('totalmarks', 10, 2)->nullable();

           
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
        Schema::dropIfExists('result_totalpsm2');
    }
};
