<?php

namespace Database\Seeders;

use App\Models\GeneralCriteria;
use Illuminate\Database\Seeder;

class GeneralCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $aspects = [
            'Kejujuran',
            'Inovatif',
            'Tanggung Jawab',
            'Motivasi Kerja',
            'Sopan Santun',
            'Kedisiplinan',
            'Komunikatif',
            'Loyalitas',
            'K3L',
            '5R',
            'Pemahaman Material',
            'Pemahaman Tambahan',
            'Kerjasama Tim',
            'Kemampuan Individu',
            'Planning Skill',
            'Ketepatan Waktu Pelaporan Bulanan',
            'Perawatan Mesin/Alat Bantu',
        ];

        foreach ($aspects as $index => $aspect) {
            GeneralCriteria::create([
                'aspect_name' => $aspect,
                'order_number' => $index + 1,
            ]);
        }
    }
}
