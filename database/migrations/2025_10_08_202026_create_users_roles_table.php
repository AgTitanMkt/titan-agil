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
        Schema::disableForeignKeyConstraints();

        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // se usuário for deletado, remove associações

            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('cascade'); // idem se papel for apagado

            $table->timestamps();
        });


        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('user_roles',function(Blueprint $table){
            $table->dropForeign(['user_id','role_id']);
        });
        Schema::dropIfExists('user_roles');
    }
};
