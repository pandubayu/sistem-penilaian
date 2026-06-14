<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentGeneralScore;
use App\Models\AssessmentTechnicalScore;
use App\Models\AssessorMapping;
use App\Models\GeneralCriteria;
use App\Models\GradingThreshold;
use App\Models\TechnicalCriteria;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $generalCriteria = GeneralCriteria::ordered()->get();

        // Ambil sebagian mapping saja (60% pertama) untuk disimulasikan "sudah dinilai"
        $mappings = AssessorMapping::with('employee.division')->get();
        $totalToFill = (int) ceil($mappings->count() * 0.6);

        foreach ($mappings->take($totalToFill) as $mapping) {
            $employee = $mapping->employee;
            $technicalCriteria = TechnicalCriteria::where('division_id', $employee->division_id)
                ->orderBy('order_number')
                ->get();

            // Kalau bagian karyawan belum punya kriteria teknis (selain 3 bagian dummy), skip
            if ($technicalCriteria->isEmpty()) {
                continue;
            }

            $assessment = Assessment::create([
                'mapping_id' => $mapping->id,
                'period_id' => $mapping->period_id,
                'assessor_id' => $mapping->assessor_id,
                'employee_id' => $mapping->employee_id,
                'assessment_date' => now()->subDays(rand(1, 10)),
                'total_score' => 0,
                'average_score' => 0,
                'grade' => null,
                'notes' => null,
            ]);

            // Isi skor teknis (random 2-4)
            foreach ($technicalCriteria as $criteria) {
                AssessmentTechnicalScore::create([
                    'assessment_id' => $assessment->id,
                    'criteria_id' => $criteria->id,
                    'score' => rand(2, 4),
                ]);
            }

            // Isi skor umum (random 2-4) untuk semua 17 aspek
            foreach ($generalCriteria as $criteria) {
                AssessmentGeneralScore::create([
                    'assessment_id' => $assessment->id,
                    'criteria_id' => $criteria->id,
                    'score' => rand(2, 4),
                ]);
            }

            // Hitung ulang total & average
            $assessment->recalculateScore();

            // Tentukan grade berdasarkan total_score & level karyawan
            $threshold = GradingThreshold::findGrade($employee->level, (float) $assessment->total_score);
            if ($threshold) {
                $assessment->update(['grade' => $threshold->grade]);
            }

            // Update mapping jadi sudah dinilai
            $mapping->update(['is_done' => true]);
        }
    }
}
