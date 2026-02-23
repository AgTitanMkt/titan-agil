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
        Schema::create('deadline_subtasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtask_id');
            $table->date('deadline_editor')->nullable();
            $table->date('deadline_copy')->nullable();
            $table->foreign('subtask_id')->references('id')->on('sub_tasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deadline_subtasks',function(Blueprint $table){
            $table->dropForeign(['subtask_id']);
        });
        Schema::dropIfExists('deadline_subtasks');
    }
};
