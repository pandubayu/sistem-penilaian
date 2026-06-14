<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentGeneralScore;
use App\Models\AssessmentTechnicalScore;
use App\Models\AssessorMapping;
use App\Models\GeneralCriteria;
use App\Models\GradingThreshold;
use App\Models\Period;
use App\Models\TechnicalCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AssessmentController extends Controller
{
    /**
     * HALAMAN 1: Pilih karyawan yang dinilai dari mapping milik penilai yang login.
     */
    public function create(Request $request)
    {
        $activePeriod = $request->attributes->get('active_period');
        $user = Auth::user();

        if (!$user->employee_id) {
            abort(403, 'Akun Anda tidak terhubung dengan data karyawan.');
        }

        // Hanya tampilkan mapping milik penilai ini, untuk periode aktif, yang BELUM dinilai
        $mappings = AssessorMapping::with(['employee.division'])
            ->where('period_id', $activePeriod->id)
            ->where('assessor_id', $user->employee_id)
            ->where('is_done', false)
            ->get();

        return view('penilaian.halaman1', [
            'activePeriod' => $activePeriod,
            'mappings' => $mappings,
        ]);
    }

    /**
     * Proses pilih karyawan dari halaman 1 -> redirect ke halaman 2 (kriteria teknis).
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'mapping_id' => ['required', 'exists:assessor_mappings,id'],
        ]);

        $mapping = AssessorMapping::find($validated['mapping_id']);

        // VALIDASI WAJIB: mapping ini harus milik penilai yang sedang login
        $this->authorizeMapping($mapping);

        return redirect()->route('penilaian.step2', $mapping->id);
    }

    /**
     * HALAMAN 2: Form kriteria teknis - DINAMIS sesuai bagian karyawan yang dinilai.
     */
    public function step2(Request $request, AssessorMapping $mapping)
    {
        $this->authorizeMapping($mapping);

        $mapping->load(['employee.division', 'period']);

        $technicalCriteria = TechnicalCriteria::where('division_id', $mapping->employee->division_id)
            ->orderBy('order_number')
            ->get();

        if ($technicalCriteria->isEmpty()) {
            return redirect()->route('penilaian.create')
                ->with('error', "Bagian \"{$mapping->employee->division->name}\" belum memiliki kriteria teknis. Hubungi HR untuk melengkapi data master.");
        }

        return view('penilaian.halaman2', [
            'mapping' => $mapping,
            'technicalCriteria' => $technicalCriteria,
            'scaleLabels' => $this->scaleLabels(),
        ]);
    }

    /**
     * Simpan jawaban halaman 2 -> redirect ke halaman 3.
     * Skor disimpan sementara di session, baru di-commit ke DB saat halaman 3 submit.
     */
    public function storeStep2(Request $request, AssessorMapping $mapping)
    {
        $this->authorizeMapping($mapping);

        $technicalCriteria = TechnicalCriteria::where('division_id', $mapping->employee->division_id)->get();

        $rules = [];
        foreach ($technicalCriteria as $criteria) {
            $rules["technical.{$criteria->id}"] = ['required', 'integer', 'between:1,4'];
        }

        $validated = $request->validate($rules);

        // Simpan sementara ke session, key unik per mapping
        session()->put("assessment.{$mapping->id}.technical", $validated['technical']);

        return redirect()->route('penilaian.step3', $mapping->id);
    }

    /**
     * HALAMAN 3: Form 17 kriteria umum + nama penilai (auto-filled).
     */
    public function step3(Request $request, AssessorMapping $mapping)
    {
        $this->authorizeMapping($mapping);

        // Pastikan halaman 2 sudah diisi sebelumnya
        if (!session()->has("assessment.{$mapping->id}.technical")) {
            return redirect()->route('penilaian.step2', $mapping->id)
                ->with('error', 'Silakan lengkapi form kriteria teknis terlebih dahulu.');
        }

        $mapping->load(['employee.division', 'assessor', 'period']);

        $generalCriteria = GeneralCriteria::ordered()->get();

        return view('penilaian.halaman3', [
            'mapping' => $mapping,
            'generalCriteria' => $generalCriteria,
            'scaleLabels' => $this->scaleLabels(),
        ]);
    }

    /**
     * Final submit: simpan semua skor (teknis dari session + umum dari form ini) ke DB,
     * hitung total, tentukan grade, tandai mapping selesai.
     */
    public function storeStep3(Request $request, AssessorMapping $mapping)
    {
        $this->authorizeMapping($mapping);

        $technicalScores = session()->get("assessment.{$mapping->id}.technical");

        if (!$technicalScores) {
            return redirect()->route('penilaian.step2', $mapping->id)
                ->with('error', 'Sesi pengisian kriteria teknis telah berakhir. Silakan ulangi.');
        }

        $generalCriteria = GeneralCriteria::all();

        $rules = ['notes' => ['nullable', 'string']];
        foreach ($generalCriteria as $criteria) {
            $rules["general.{$criteria->id}"] = ['required', 'integer', 'between:1,4'];
        }

        $validated = $request->validate($rules);

        // Cegah double-submit: cek lagi apakah assessment sudah ada untuk mapping ini
        if ($mapping->assessment()->exists()) {
            return redirect()->route('penilaian.create')
                ->with('error', 'Penilaian untuk karyawan ini sudah pernah disimpan sebelumnya.');
        }

        // 1. Buat record assessment utama
        $assessment = Assessment::create([
            'mapping_id' => $mapping->id,
            'period_id' => $mapping->period_id,
            'assessor_id' => $mapping->assessor_id,
            'employee_id' => $mapping->employee_id,
            'assessment_date' => now(),
            'total_score' => 0,
            'average_score' => 0,
            'grade' => null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // 2. Simpan skor teknis (dari session, hasil halaman 2)
        foreach ($technicalScores as $criteriaId => $score) {
            AssessmentTechnicalScore::create([
                'assessment_id' => $assessment->id,
                'criteria_id' => $criteriaId,
                'score' => $score,
            ]);
        }

        // 3. Simpan skor umum (dari form halaman 3)
        foreach ($validated['general'] as $criteriaId => $score) {
            AssessmentGeneralScore::create([
                'assessment_id' => $assessment->id,
                'criteria_id' => $criteriaId,
                'score' => $score,
            ]);
        }

        // 4. Hitung total & average
        $assessment->recalculateScore();

        // 5. Tentukan grade berdasarkan total_score & level karyawan yang dinilai
        $employee = $mapping->employee;
        $threshold = GradingThreshold::findGrade($employee->level, (float) $assessment->total_score);
        if ($threshold) {
            $assessment->update(['grade' => $threshold->grade]);
        }

        // 6. Tandai mapping selesai
        $mapping->update(['is_done' => true]);

        // 7. Bersihkan session
        session()->forget("assessment.{$mapping->id}");

        ActivityLog::record('submit_assessment', $assessment, null, $assessment->toArray());

        return redirect()->route('dashboard')
            ->with('success', "Penilaian untuk \"{$employee->name}\" berhasil disimpan.");
    }

    /**
     * Karyawan melihat hasil penilaian dirinya sendiri (semua periode).
     */
    public function myResult()
    {
        $user = Auth::user();

        if (!$user->employee_id) {
            abort(403, 'Akun Anda tidak terhubung dengan data karyawan.');
        }

        $assessments = Assessment::with(['period', 'assessor', 'technicalScores.criteria', 'generalScores.criteria'])
            ->where('employee_id', $user->employee_id)
            ->orderByDesc('assessment_date')
            ->get();

        return view('penilaian.hasil-saya', [
            'employee' => $user->employee,
            'assessments' => $assessments,
        ]);
    }

    /**
     * VALIDASI INTI: pastikan mapping ini benar-benar milik penilai yang login.
     * Penilai TIDAK BISA mengakses/menilai karyawan di luar mapping-nya.
     */
    private function authorizeMapping(AssessorMapping $mapping): void
    {
        $user = Auth::user();

        if ($mapping->assessor_id !== $user->employee_id) {
            abort(403, 'Anda tidak memiliki izin untuk menilai karyawan ini. Karyawan ini bukan bagian dari mapping penilaian Anda.');
        }

        $activePeriod = Period::active()->first();

        if (!$activePeriod || $mapping->period_id !== $activePeriod->id) {
            abort(403, 'Mapping ini bukan untuk periode yang sedang aktif.');
        }

        if ($mapping->is_done) {
            throw ValidationException::withMessages([
                'mapping_id' => 'Karyawan ini sudah Anda nilai sebelumnya.',
            ]);
        }
    }

    /**
     * Label skala nilai 1-4, dipakai di halaman 2 & 3.
     */
    private function scaleLabels(): array
    {
        return [
            1 => 'Tidak Memuaskan',
            2 => 'Perlu Peningkatan',
            3 => 'Cukup Memuaskan',
            4 => 'Sesuai Harapan',
        ];
    }
}
