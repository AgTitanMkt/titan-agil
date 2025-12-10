<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('platforms')->insert([
            ['name' => 'Meta Ads'],
            ['name' => 'TikTok Ads'],
            ['name' => 'Google Ads'],
            ['name' => 'YouTube'],
            ['name' => 'Kwai'],
            ['name' => 'Taboola'],
            ['name' => 'Native Ads'],
        ]);
    }
}
