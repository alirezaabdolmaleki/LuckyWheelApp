<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AwardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('awards')->insert([
            ['title' => 'X', 'coefficient' => 1, 'inventory' => 10],
            ['title' => 'Y', 'coefficient' => 2, 'inventory' => 10],
            ['title' => 'empty', 'coefficient' => 2, 'inventory' => 10],
        ]);
    }
}
