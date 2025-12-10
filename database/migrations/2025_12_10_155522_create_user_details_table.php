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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions');

            $table->unsignedBigInteger('squad_id')->nullable();
            $table->foreign('squad_id')->references('id')->on('squads');

            $table->unsignedBigInteger('platform_id')->nullable();
            $table->foreign('platform_id')->references('id')->on('platforms');

            $table->string('personal_email')->nullable();
            $table->date('start_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');

            // Gestor responsável
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details',function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['position_id']);
            $table->dropForeign(['squad_id']);
            $table->dropForeign(['platform_id']);
            $table->dropForeign(['manager_id']);
        });
        Schema::dropIfExists('user_details');
    }
};
