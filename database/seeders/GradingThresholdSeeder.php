<?php

namespace Database\Seeders;

use App\Models\GradingThreshold;
use Illuminate\Database\Seeder;

class GradingThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            // Level 2 - Ka. Bagian
            [
                'employee_level' => 2,
                'grade' => 'A',
                'min_score' => 291,
                'max_score' => null,
                'reward_text' => 'Diajukan Kartap & Promosi, tunjangan Rp 200.000/3 bulan',
                'punishment_text' => '-',
            ],
            [
                'employee_level' => 2,
                'grade' => 'B',
                'min_score' => 218,
                'max_score' => 290,
                'reward_text' => 'Kontrak 1 tahun, tunjangan Rp 75.000/3 bulan',
                'punishment_text' => '-',
            ],
            [
                'employee_level' => 2,
                'grade' => 'C',
                'min_score' => 151,
                'max_score' => 217,
                'reward_text' => '-',
                'punishment_text' => 'Kontrak 6 bulan + pelatihan 2 bulan / turun jabatan',
            ],
            [
                'employee_level' => 2,
                'grade' => 'D',
                'min_score' => 0,
                'max_score' => 150,
                'reward_text' => '-',
                'punishment_text' => 'Diberhentikan',
            ],

            // Level 1 - Operator/Staff
            [
                'employee_level' => 1,
                'grade' => 'A',
                'min_score' => 291,
                'max_score' => null,
                'reward_text' => 'Diajukan Kartap & Promosi, tunjangan Rp 75.000/3 bulan',
                'punishment_text' => '-',
            ],
            [
                'employee_level' => 1,
                'grade' => 'B',
                'min_score' => 141,
                'max_score' => 290,
                'reward_text' => 'Kontrak 1 tahun',
                'punishment_text' => '-',
            ],
            [
                'employee_level' => 1,
                'grade' => 'C',
                'min_score' => 101,
                'max_score' => 140,
                'reward_text' => '-',
                'punishment_text' => 'Kontrak 6 bulan + pelatihan 2 bulan',
            ],
            [
                'employee_level' => 1,
                'grade' => 'D',
                'min_score' => 0,
                'max_score' => 100,
                'reward_text' => '-',
                'punishment_text' => 'Diberhentikan',
            ],
        ];

        foreach ($thresholds as $threshold) {
            GradingThreshold::create($threshold);
        }
    }
}
