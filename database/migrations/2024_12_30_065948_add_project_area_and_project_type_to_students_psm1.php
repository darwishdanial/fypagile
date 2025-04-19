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
        Schema::table('students_psm1', function (Blueprint $table) {
            $table->string('project_area')->after('title'); // Adds 'project_area' after 'title'
            $table->string('project_area_ai')->after('project_area');
            $table->string('project_type')->after('project_area'); // Adds 'project_type' after 'project_area'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students_psm1', function (Blueprint $table) {
            $table->dropColumn('project_area');
            $table->dropColumn('project_area_ai');
            $table->dropColumn('project_type');
        });
    }
};
