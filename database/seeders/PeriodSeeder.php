<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periods = [
            ['name' => 'Q1 2026', 'type' => 'Q1', 'start_date' => '2026-01-01', 'end_date' => '2026-03-31', 'is_active' => true],
            ['name' => 'Q2 2026', 'type' => 'Q2', 'start_date' => '2026-04-01', 'end_date' => '2026-06-30', 'is_active' => false],
            ['name' => 'Q3 2026', 'type' => 'Q3', 'start_date' => '2026-07-01', 'end_date' => '2026-09-30', 'is_active' => false],
            ['name' => 'Q4 2026', 'type' => 'Q4', 'start_date' => '2026-10-01', 'end_date' => '2026-12-31', 'is_active' => false],
            ['name' => 'Training Bulan 1', 'type' => 'Training 1', 'start_date' => '2026-01-01', 'end_date' => '2026-01-31', 'is_active' => false],
            ['name' => 'Training Bulan 2', 'type' => 'Training 2', 'start_date' => '2026-02-01', 'end_date' => '2026-02-28', 'is_active' => false],
            ['name' => 'Training Bulan 3', 'type' => 'Training 3', 'start_date' => '2026-03-01', 'end_date' => '2026-03-31', 'is_active' => false],
            ['name' => 'Masa Percobaan 1', 'type' => 'Percobaan 1', 'start_date' => '2026-01-01', 'end_date' => '2026-01-31', 'is_active' => false],
            ['name' => 'Masa Percobaan 2', 'type' => 'Percobaan 2', 'start_date' => '2026-02-01', 'end_date' => '2026-02-28', 'is_active' => false],
            ['name' => 'Masa Percobaan 3', 'type' => 'Percobaan 3', 'start_date' => '2026-03-01', 'end_date' => '2026-03-31', 'is_active' => false],
            ['name' => 'Extra Masa Percobaan', 'type' => 'Extra Percobaan', 'start_date' => '2026-04-01', 'end_date' => '2026-04-30', 'is_active' => false],
        ];

        foreach ($periods as $period) {
            Period::create($period);
        }
    }
}
