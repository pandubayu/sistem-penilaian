<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentReportExport;
use App\Models\Assessment;
use App\Models\Division;
use App\Models\Period;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Halaman laporan raport: peringkat per level, nilai total, grade, reward/punishment.
     */
    public function index(Request $request)
    {
        $periods = Period::orderByDesc('start_date')->get();
        $divisions = Division::orderBy('name')->get();

        $selectedPeriodId = $request->period_id ?? Period::active()->first()?->id ?? $periods->first()?->id;
        $selectedLevel = $request->level ?? null;

        $assessments = $this->getReportData($selectedPeriodId, $selectedLevel, $request->division_id);

        return view('laporan.raport', [
            'periods' => $periods,
            'divisions' => $divisions,
            'selectedPeriodId' => $selectedPeriodId,
            'selectedLevel' => $selectedLevel,
            'selectedDivisionId' => $request->division_id,
            'assessmentsLevel1' => $assessments->where('employee.level', 1)->values(),
            'assessmentsLevel2' => $assessments->where('employee.level', 2)->values(),
        ]);
    }

    /**
     * Export laporan ke PDF, dikelompokkan per level dengan peringkat.
     */
    public function exportPdf(Request $request)
    {
        $selectedPeriodId = $request->period_id ?? Period::active()->first()?->id;
        $period = Period::find($selectedPeriodId);

        $assessments = $this->getReportData($selectedPeriodId, null, $request->division_id);

        $pdf = Pdf::loadView('laporan.export-pdf', [
            'period' => $period,
            'assessmentsLevel1' => $assessments->where('employee.level', 1)->values(),
            'assessmentsLevel2' => $assessments->where('employee.level', 2)->values(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        $fileName = 'Laporan-Penilaian-' . str_replace(' ', '-', $period->name ?? 'periode') . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export laporan ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $selectedPeriodId = $request->period_id ?? Period::active()->first()?->id;
        $period = Period::find($selectedPeriodId);

        $assessments = $this->getReportData($selectedPeriodId, null, $request->division_id);

        $fileName = 'Laporan-Penilaian-' . str_replace(' ', '-', $period->name ?? 'periode') . '.xlsx';

        return Excel::download(new AssessmentReportExport($assessments), $fileName);
    }

    /**
     * Ambil data assessment untuk laporan: 1 assessment per employee (gabungan rata-rata
     * dari semua penilai), beserta grade & reward/punishment.
     *
     * Catatan: karena 1 karyawan dinilai oleh beberapa penilai (atasan + rekan),
     * "hasil akhir" = rata-rata dari semua assessment milik karyawan tersebut
     * di periode yang sama (sesuai requirement: bobot sementara 1:1).
     */
    private function getReportData(?int $periodId, ?int $level = null, ?int $divisionId = null)
    {
        if (!$periodId) {
            return collect();
        }

        $assessments = Assessment::with(['employee.division', 'period'])
            ->where('period_id', $periodId)
            ->when($level, fn ($q) => $q->whereHas('employee', fn ($q2) => $q2->where('level', $level)))
            ->when($divisionId, fn ($q) => $q->whereHas('employee', fn ($q2) => $q2->where('division_id', $divisionId)))
            ->get();

        // Group per employee, gabungkan rata-rata dari semua penilai
        $grouped = $assessments->groupBy('employee_id')->map(function ($items) {
            $employee = $items->first()->employee;
            $period = $items->first()->period;

            $finalTotal = round($items->avg('total_score'), 2);

            $threshold = \App\Models\GradingThreshold::findGrade($employee->level, $finalTotal);

            return (object) [
                'employee' => $employee,
                'period' => $period,
                'jumlah_penilai' => $items->count(),
                'total_score' => $finalTotal,
                'average_score' => round($items->avg('average_score'), 2),
                'grade' => $threshold?->grade,
                'reward_text' => $threshold?->reward_text,
                'punishment_text' => $threshold?->punishment_text,
            ];
        });

        // Urutkan: total_score tertinggi = peringkat 1, lalu tambahkan nomor peringkat
        return $grouped->sortByDesc('total_score')->values()->map(function ($item, $index) {
            $item->rank = $index + 1;

            return $item;
        });
    }
}
