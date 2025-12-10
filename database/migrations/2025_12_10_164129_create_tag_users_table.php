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
        Schema::create('tag_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tag')->unique();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tag_users',function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('tag_users');
    }
};
