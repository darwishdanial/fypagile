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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('matricNo');
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->boolean('isSupervisorPSM1')->nullable();
            $table->boolean('isProposalPanel')->nullable();
            $table->boolean('isPanelPSM1')->nullable();
            $table->boolean('isSupervisorPSM2')->nullable();
            $table->boolean('isPanelPSM2')->nullable();
            $table->boolean('isArchivePSM1')->nullable();
            $table->boolean('isArchivePSM2')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
