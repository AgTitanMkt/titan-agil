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
        Schema::create('vw_creatives_performance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->nullable()->index();
            $table->string('creative_code')->nullable()->index();
            $table->string('origem')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('agent_name')->nullable();
            $table->unsignedBigInteger('redtrack_id')->nullable()->index();
            $table->string('rt_ad')->nullable();
            $table->string('source')->nullable()->index();
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('roi', 10, 4)->default(0);
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->decimal('roas', 10, 2)->default(0);
            $table->decimal('ctr', 10, 4)->nullable();
            $table->decimal('cpm', 10, 4)->nullable();
            $table->timestamp('redtrack_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vw_creatives_performance');
    }
};
