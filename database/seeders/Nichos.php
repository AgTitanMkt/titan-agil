<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Nichos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nichos')->insert([
            ['id'=>1, 'sigla' => 'MM', 'name' => 'Memória'],
            ['id'=>2, 'sigla' => 'WL', 'name' => 'Emagrecimento'],
            ['id'=>3, 'sigla' => 'ED', 'name' => 'E.D'],
            ['id'=>4, 'sigla' => 'DB', 'name' => 'Diabetes'],
            ['id'=>5, 'sigla' => 'PR', 'name' => 'Próstata'],
            ['id'=>6, 'sigla' => 'VS', 'name' => 'Visão'],
            ['id'=>7, 'sigla' => 'NR', 'name' => 'Neuropatia'],
            ['id'=>8, 'sigla' => 'TN', 'name' => 'Tinnitus'],
        ]);
    }
}
