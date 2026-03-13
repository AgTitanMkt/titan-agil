<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassificarUsuariosSeeder extends Seeder
{
    public function run()
    {
        // COPYS EXTERNOS
        
        DB::table('users')
            ->where('name', 'LIKE', 'CEGEX%')
            ->orWhere('name', 'LIKE', 'CEBH%')
            ->orWhere('name', 'LIKE', 'CEXMX%')
            ->orWhere('name', 'LIKE', 'CEDAN%')
            ->orWhere('name', 'LIKE', 'CERB%')
            ->orWhere('name', 'LIKE', 'CEIMP%')
            ->update(['tipo_colaborador' => 'EX']);

        // O RESTANTE E INTERNO (IN)
        
        DB::table('users')
            ->whereNull('tipo_colaborador')
            ->orWhere('tipo_colaborador', '!=', 'EX')
            ->update(['tipo_colaborador' => 'IN']);
            
        $this->command->info('Usuários classificados com sucesso!');
    }
}