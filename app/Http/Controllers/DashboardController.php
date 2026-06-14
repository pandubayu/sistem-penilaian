<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessorMapping;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'hr' => $this->hrDashboard(),
            'penilai' => $this->penilaiDashboard($user),
            'karyawan' => $this->karyawanDashboard($user),
            default => view('dashboard.hr'),
        };
    }

    /**
     * Dashboard HR: statistik penilaian global
     */
    private function hrDashboard()
    {
        $activePeriod = Period::active()->first();

        $totalMappings = 0;
        $doneMappings = 0;
        $avgPerDivision = collect();

        if ($activePeriod) {
            $totalMappings = AssessorMapping::where('period_id', $activePeriod->id)->count();
            $doneMappings = AssessorMapping::where('period_id', $activePeriod->id)->where('is_done', true)->count();

            // Rata-rata nilai per bagian, untuk periode aktif
            $avgPerDivision = Division::query()
                ->with([])
                ->get()
                ->map(function ($division) use ($activePeriod) {
                    $avg = Assessment::where('period_id', $activePeriod->id)
                        ->whereHas('employee', function ($q) use ($division) {
                            $q->where('division_id', $division->id);
                        })
                        ->avg('average_score');

                    return [
                        'division' => $division->name,
                        'average' => $avg ? round($avg, 2) : null,
                    ];
                })
                ->filter(fn ($item) => $item['average'] !== null)
                ->values();
        }

        $belumMappings = $totalMappings - $doneMappings;

        return view('dashboard.hr', [
            'activePeriod' => $activePeriod,
            'totalMappings' => $totalMappings,
            'doneMappings' => $doneMappings,
            'belumMappings' => $belumMappings,
            'avgPerDivision' => $avgPerDivision,
            'totalEmployees' => Employee::where('is_active', true)->count(),
        ]);
    }

    /**
     * Dashboard Penilai: daftar karyawan yang wajib dinilai + status
     */
    private function penilaiDashboard($user)
    {
        $activePeriod = Period::active()->first();
        $mappings = collect();

        if ($activePeriod && $user->employee_id) {
            $mappings = AssessorMapping::with(['employee.division'])
                ->where('period_id', $activePeriod->id)
                ->where('assessor_id', $user->employee_id)
                ->orderBy('is_done')
                ->get();
        }

        return view('dashboard.penilai', [
            'activePeriod' => $activePeriod,
            'mappings' => $mappings,
            'totalTugas' => $mappings->count(),
            'sudahDinilai' => $mappings->where('is_done', true)->count(),
            'belumDinilai' => $mappings->where('is_done', false)->count(),
        ]);
    }

    /**
     * Dashboard Karyawan: lihat hasil penilaian diri sendiri
     */
    private function karyawanDashboard($user)
    {
        $assessments = collect();

        if ($user->employee_id) {
            $assessments = Assessment::with(['period', 'assessor'])
                ->where('employee_id', $user->employee_id)
                ->orderByDesc('assessment_date')
                ->get();
        }

        return view('dashboard.karyawan', [
            'employee' => $user->employee,
            'assessments' => $assessments,
        ]);
    }
}
