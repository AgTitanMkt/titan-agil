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
        Schema::create('validated_creatives', function (Blueprint $table) {
            $table->id();

            $table->string('ad_code')->unique();

            // métricas consolidadas
            $table->integer('total_conversions');
            $table->decimal('total_cost', 12, 2);
            $table->decimal('total_profit', 12, 2);
            $table->decimal('roi', 10, 4);

            // estágios
            $table->boolean('is_potential')->default(false);
            $table->boolean('is_validated')->default(false);

            // datas de marco
            $table->timestamp('potential_at')->nullable();
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validated_creatives');
    }
};
