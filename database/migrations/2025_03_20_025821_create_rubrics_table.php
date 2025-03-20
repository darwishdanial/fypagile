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
            $table->decimal('total_weight');
            $table->string(column: 'PSMType');
            $table->boolean('isSupervisorPSM1');
            $table->boolean('isPanelPSM1');
            $table->boolean('isSupervisorPSM2');
            $table->boolean('isPanelPSM2');
            $table->timestamps();

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
