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
            ['id' => 1,  'sigla' => 'MM', 'name' => 'Memória',        'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 2,  'sigla' => 'WL', 'name' => 'Emagrecimento',  'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 3,  'sigla' => 'ED', 'name' => 'E.D',            'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 4,  'sigla' => 'DB', 'name' => 'Diabetes',       'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 5,  'sigla' => 'PR', 'name' => 'Próstata',       'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 6,  'sigla' => 'VS', 'name' => 'Visão',          'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 7,  'sigla' => 'NR', 'name' => 'Neuropatia',     'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 8,  'sigla' => 'TN', 'name' => 'Tinnitus',       'description' => null, 'created_at' => null, 'updated_at' => null],

            ['id' => 9,  'sigla' => 'FW', 'name' => 'Emagrecimento',  'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'sigla' => 'FT', 'name' => 'Tinnitus',       'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'sigla' => 'FE', 'name' => 'E.D',            'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 12, 'sigla' => 'FP', 'name' => 'Próstata',       'description' => null, 'created_at' => null, 'updated_at' => null],

            ['id' => 13, 'sigla' => 'YE', 'name' => 'E.D',            'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'sigla' => 'YM', 'name' => 'Memória',        'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 15, 'sigla' => 'YD', 'name' => 'Diabetes',       'description' => null, 'created_at' => null, 'updated_at' => null],
            ['id' => 16, 'sigla' => 'YW', 'name' => 'Emagrecimento',  'description' => null, 'created_at' => null, 'updated_at' => null],

            ['id' => 17, 'sigla' => 'FD', 'name' => 'Diabetes',       'description' => null, 'created_at' => null, 'updated_at' => null],
        ]);
    }
}
