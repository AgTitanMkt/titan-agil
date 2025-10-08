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
        Schema::disableForeignKeyConstraints();

        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->onDelete('cascade'); // se tarefa for deletada, remove arquivos vinculados

            $table->string('file_type');
            $table->longText('file_url');

            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // mantém arquivo, mas zera o campo se o user sair

            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_files', function (Blueprint $table) {
            $table->dropForeign(['task_id', 'uploaded_by']);
        });
        Schema::dropIfExists('tasks_files');
    }
};
