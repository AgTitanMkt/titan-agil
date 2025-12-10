<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SquadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('squads')->insert([
            ['name' => 'Squad Especial Gestores'],
            ['name' => 'Squad Copy'],
            ['name' => 'Squad Edição'],
        ]);
    }
}
