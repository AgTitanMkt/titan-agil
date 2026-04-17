<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('redtrack_reports', function (Blueprint $table) {
            $table->decimal('revenue', 15, 4)->default(0)->after('cost');
        });
    }

    public function down(): void
    {
        Schema::table('redtrack_reports', function (Blueprint $table) {
            $table->dropColumn('revenue');
        });
    }
};
