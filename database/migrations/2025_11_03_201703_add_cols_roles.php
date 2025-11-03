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
        Schema::table('vw_creatives_performance', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->index()->after('agent_name');
            $table->text('role_name')->after('agent_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vw_creatives_performance', function (Blueprint $table) {
            $table->dropColumn(['role_id','role_name']);
        });
    }
};
