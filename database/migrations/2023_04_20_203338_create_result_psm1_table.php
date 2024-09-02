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
        // Schema::create('result_psm', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('studentId')->nullable()->references('id')->on('students'); 
        //     $table->foreignId('svId')->nullable()->references('id')->on('supervisors'); 
        //     $table->foreignId('panelId')->nullable()->references('id')->on('users'); 
        //     $table->string("logbook");
        //     $table->string("meeting");
        //     $table->string("ethic");
        //     $table->string("independent");
        //     $table->string("chapter1");
        //     $table->string("chapter2");
        //     $table->string("format");
        //     $table->string("citation");
        //     $table->string("chapter3");
        //     $table->string("chapter4");
        //     $table->string("format2");
        //     $table->string("citation2");
        //     $table->string("abstract");
        //     $table->string("complete1");
        //     $table->string("complete2");
        //     $table->string("complete3");
        //     $table->string("complete4");
        //     $table->string("format3");
        //     $table->string("citation3");
        //     $table->timestamps();
        // });

        Schema::create('result_psm1', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentId')->nullable()->references('id')->on('students_psm1'); 
            $table->string("type")->nullable();
            // $table->foreignId('svId')->nullable()->references('id')->on('supervisors'); 
            $table->foreignId('typeId')->nullable()->references('id')->on('users'); 
            $table->decimal("supervision", 10, 2)->nullable();
            $table->decimal("progressreport1", 10, 2)->nullable();
            $table->decimal("progressreport2", 10, 2)->nullable();
            $table->decimal("finalreport", 10, 2)->nullable();
            $table->decimal("design", 10, 2)->nullable();
            $table->decimal("presentation", 10, 2)->nullable();
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
        Schema::dropIfExists('result_psm');
    }
};
