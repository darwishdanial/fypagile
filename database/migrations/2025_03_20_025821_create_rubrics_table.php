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
        Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('total_weight')->unsigned();
            $table->string(column: 'PSMType');
            $table->string(column: 'session');
            $table->boolean('isDevelopment');
            $table->boolean('isResearch');
            $table->boolean('isEnable');
            $table->tinyInteger('roleType')->unsigned();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrics');
    }
};
