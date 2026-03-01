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
        Schema::create('subtask_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('event'); // created, assigned, status_changed, rejected, approved...
            $table->text('description')->nullable();

            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subtask_histories', function (Blueprint $table) {
            $table->dropForeign(['sub_task_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('subtask_histories');
    }
};
