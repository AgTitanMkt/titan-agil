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
        Schema::table('redtrack_reports', function (Blueprint $table) {
            $table->text('ad_code')->after('normalized_rt_ad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redtrack_reports', function (Blueprint $table) {
            $table->dropColumn(['ad_code']);
        });
    }
};
