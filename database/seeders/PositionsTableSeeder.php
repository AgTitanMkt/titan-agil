<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('positions')->insert([
            // Copywriting (1)
            ['name' => 'Copywriter Júnior', 'department_id' => 1],
            ['name' => 'Copywriter Pleno', 'department_id' => 1],
            ['name' => 'Copywriter Sênior', 'department_id' => 1],

            // Edição (2)
            ['name' => 'Editor Júnior', 'department_id' => 2],
            ['name' => 'Editor Pleno', 'department_id' => 2],
            ['name' => 'Editor Sênior', 'department_id' => 2],

            // Design (3)
            ['name' => 'Designer Júnior', 'department_id' => 3],
            ['name' => 'Designer Pleno', 'department_id' => 3],
            ['name' => 'Designer Sênior', 'department_id' => 3],

            // Tráfego (4)
            ['name' => 'Gestor de Tráfego', 'department_id' => 4],
            ['name' => 'Analista de Tráfego', 'department_id' => 4],

            // Financeiro (5)
            ['name' => 'Assistente Financeiro', 'department_id' => 5],
            ['name' => 'Analista Financeiro', 'department_id' => 5],

            // Gestão (6)
            ['name' => 'Gestor', 'department_id' => 6],
            ['name' => 'Coordenador', 'department_id' => 6],

            // Diretoria (7)
            ['name' => 'Diretor', 'department_id' => 7],
            ['name' => 'Head', 'department_id' => 7],
        ]);
    }
}
