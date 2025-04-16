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
        Schema::create('ai_data', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('project_area')->unsigned();
            $table->tinyInteger('project_type')->unsigned();
            $table->tinyInteger('panel_name')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_data');
    }
};
