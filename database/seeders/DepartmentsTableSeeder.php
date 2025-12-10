<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('departments')->insert([
            ['name' => 'Copywriting'],
            ['name' => 'Edição'],
            ['name' => 'Design'],
            ['name' => 'Tráfego'],
            ['name' => 'Financeiro'],
            ['name' => 'Gestão'],
            ['name' => 'Diretoria'],
        ]);
    }
}
