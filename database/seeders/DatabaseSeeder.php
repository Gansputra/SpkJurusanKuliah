<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CriteriaSeeder::class,
            AlternativeSeeder::class,
            PairwiseMatrixSeeder::class,
            AlternativeScoreSeeder::class,
        ]);
    }
}
