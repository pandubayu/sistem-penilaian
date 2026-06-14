<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DivisionSeeder::class,
            UserSeeder::class,
            PeriodSeeder::class,
            TechnicalCriteriaSeeder::class,
            GeneralCriteriaSeeder::class,
            GradingThresholdSeeder::class,
            MappingSeeder::class,
            AssessmentSeeder::class,
        ]);
    }
}
