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
        Schema::create('redtrack_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('source')->index();
            $table->string('alias')->nullable();
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->decimal('roi', 8, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redtrack_reports');
    }
};
