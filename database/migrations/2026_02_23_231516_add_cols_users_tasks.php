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
        Schema::table('user_tasks', function (Blueprint $table) {
            $table->enum('status', ['ASSIGNED', 'IN_PROGRESS', 'DONE', 'REJECTED'])->default('ASSIGNED')->after('sub_task_id');
            $table->timestamp('started_at')->nullable()->after('sub_task_id');
            $table->timestamp('completed_at')->nullable()->after('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_tasks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('started_at');
            $table->dropColumn('completed_at');
        });
    }
};
