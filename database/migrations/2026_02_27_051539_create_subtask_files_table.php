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
        Schema::create('subtask_files', function (Blueprint $table) {
            $table->id();

            $table->foreignId('subtask_id')
                ->constrained('sub_tasks')
                ->cascadeOnDelete();

            $table->string('file_type');
            $table->string('file_url');

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('subtask_files');
    }
};
