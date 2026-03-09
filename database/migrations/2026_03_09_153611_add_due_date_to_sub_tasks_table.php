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
        Schema::table('sub_tasks', function (Blueprint $table) {
            // coluna nova due_date como timestamp permitindo nulo  para nao dar bo nas tarefas ja existentes
            $table->timestamp('due_date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            // caso precisar reverter o laravel sabe como apagar a coluna
            $table->dropColumn('due_date');
        });
    }
};
