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
            $table->dropForeign(['executed_by']);
            $table->dropColumn(['executed_by']);
            $table->dropForeign(['revised_by']);
            $table->dropColumn(['revised_by']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            //
        });
    }
};
