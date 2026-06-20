<?php

namespace App\Http\Controllers;


use App\Models\ActivityLog;
use App\Models\AssessorMapping;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MappingController extends Controller
{
    /**
     * Tampilkan daftar mapping, bisa difilter per periode dan per bagian.
     */
    public function index(Request $request)
    {
        $periods = Period::orderByDesc('start_date')->get();
        $divisions = Division::orderBy('name')->get();

        // Default: tampilkan periode aktif
        $selectedPeriodId = $request->period_id ?? Period::active()->first()?->id ?? $periods->first()?->id;

        $mappings = AssessorMapping::with(['assessor.division', 'employee.division', 'period'])
            ->where('period_id', $selectedPeriodId)
            ->when($request->division_id, function ($q, $divisionId) {
                $q->whereHas('employee', fn ($q2) => $q2->where('division_id', $divisionId));
            })
            ->orderBy('employee_id')
            ->orderByDesc('assessor_type')
            ->paginate(20)
            ->withQueryString();

        return view('mapping.index', [
            'mappings' => $mappings,
            'periods' => $periods,
            'divisions' => $divisions,
            'selectedPeriodId' => $selectedPeriodId,
        ]);
    }

    /**
     * Form untuk membuat mapping baru.
     * HR pilih: periode, karyawan yang dinilai, lalu daftar penilai (atasan + rekan).
     */
    public function create(Request $request)
    {
        $periods = Period::orderByDesc('start_date')->get();
        $selectedPeriodId = $request->period_id ?? Period::active()->first()?->id ?? $periods->first()?->id;

        $employees = Employee::with('division')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Daftar mapping yang sudah ada di periode ini, untuk ditampilkan sebagai info/preview
        $existingMappings = AssessorMapping::with(['assessor', 'employee'])
            ->where('period_id', $selectedPeriodId)
            ->get()
            ->groupBy('employee_id');

        return view('mapping.create', [
            'periods' => $periods,
            'selectedPeriodId' => $selectedPeriodId,
            'employees' => $employees,
            'existingMappings' => $existingMappings,
        ]);
    }

    /**
     * Simpan satu atau beberapa mapping sekaligus.
     * Input: period_id, employee_id (yang dinilai), assessors[] (array of {id, type})
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_id' => ['required', 'exists:periods,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'assessors' => ['required', 'array', 'min:1'],
            'assessors.*.assessor_id' => ['required', 'exists:employees,id'],
            'assessors.*.assessor_type' => ['required', Rule::in(['atasan', 'rekan'])],
        ]);

        // Validasi: karyawan tidak bisa menilai dirinya sendiri
        foreach ($validated['assessors'] as $assessorData) {
            if ($assessorData['assessor_id'] == $validated['employee_id']) {
                throw ValidationException::withMessages([
                    'assessors' => 'Karyawan tidak bisa menilai dirinya sendiri.',
                ]);
            }
        }

        // Validasi: maksimal 1 "atasan" per karyawan per periode
        $atasanCount = collect($validated['assessors'])->where('assessor_type', 'atasan')->count();
        if ($atasanCount > 1) {
            throw ValidationException::withMessages([
                'assessors' => 'Maksimal hanya 1 atasan langsung per karyawan.',
            ]);
        }

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($validated['assessors'] as $assessorData) {
            // Skip kalau mapping sudah ada (cegah duplikat, sesuai unique constraint di DB)
            $exists = AssessorMapping::where('period_id', $validated['period_id'])
                ->where('assessor_id', $assessorData['assessor_id'])
                ->where('employee_id', $validated['employee_id'])
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $mapping = AssessorMapping::create([
                'period_id' => $validated['period_id'],
                'assessor_id' => $assessorData['assessor_id'],
                'employee_id' => $validated['employee_id'],
                'assessor_type' => $assessorData['assessor_type'],
                'is_done' => false,
            ]);

            ActivityLog::record('create_mapping', $mapping, null, $mapping->toArray());

            $createdCount++;
        }

        $message = "{$createdCount} mapping berhasil ditambahkan.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} mapping dilewati karena sudah ada sebelumnya.";
        }

        return redirect()->route('mapping.index', ['period_id' => $validated['period_id']])
            ->with('success', $message);
    }

    /**
     * Hapus satu mapping.
     * Tidak bisa dihapus kalau sudah ada assessment terkait (sudah dinilai).
     */
    public function destroy(AssessorMapping $mapping)
    {
        $oldData = $mapping->toArray();
        $periodId = $mapping->period_id;

        if ($mapping->is_done || $mapping->assessment()->exists()) {
            return redirect()->route('mapping.index', ['period_id' => $periodId])
                ->with('error', 'Mapping tidak bisa dihapus karena penilai sudah mengisi penilaian untuk mapping ini.');
        }

        $mapping->delete();

       ActivityLog::record('delete_mapping', null, $oldData, null);

        return redirect()->route('mapping.index', ['period_id' => $periodId])
            ->with('success', 'Mapping berhasil dihapus.');
    }

    /**
     * RESET 1 MAPPING: hapus assessment terkait, kembalikan is_done jadi false.
     * Dipakai HR saat penilai salah input dan perlu mengisi ulang.
     */
    public function reset(AssessorMapping $mapping)
    {
        if (!$mapping->is_done) {
            return redirect()->route('mapping.index', ['period_id' => $mapping->period_id])
                ->with('error', 'Mapping ini belum dinilai, tidak ada yang perlu di-reset.');
        }

        $assessment = $mapping->assessment;
        $oldData = [
            'mapping' => $mapping->toArray(),
            'assessment' => $assessment?->toArray(),
            'reset_by' => auth()->user()->name,
        ];

        if ($assessment) {
            $assessment->technicalScores()->delete();
            $assessment->generalScores()->delete();
            $assessment->delete();
        }

        $mapping->update(['is_done' => false]);

        ActivityLog::record('reset_mapping', $mapping, $oldData, ['is_done' => false]);

        return redirect()->route('mapping.index', ['period_id' => $mapping->period_id])
            ->with('success', "Penilaian \"{$mapping->assessor->name}\" untuk \"{$mapping->employee->name}\" berhasil di-reset. Penilai bisa mengisi ulang.");
    }

    /**
     * RESET SEMUA MAPPING DI 1 PERIODE: hapus semua assessment, kembalikan
     * semua mapping jadi belum dinilai. Dipakai HR untuk "mulai ulang total"
     * satu periode (misal ada kesalahan massal atau data testing).
     */
    public function resetPeriod(Period $periode)
    {
        $mappings = AssessorMapping::with('assessment')->where('period_id', $periode->id)->get();
        $totalReset = 0;

        foreach ($mappings as $mapping) {
            if ($mapping->assessment) {
                $mapping->assessment->technicalScores()->delete();
                $mapping->assessment->generalScores()->delete();
                $mapping->assessment->delete();
                $totalReset++;
            }
        }

        AssessorMapping::where('period_id', $periode->id)->update(['is_done' => false]);

        ActivityLog::record(
            'reset_period_mapping',
            $periode,
            ['total_assessment_dihapus' => $totalReset, 'reset_by' => auth()->user()->name],
            ['period' => $periode->name]
        );

        return redirect()->route('mapping.index', ['period_id' => $periode->id])
            ->with('success', "Berhasil reset {$totalReset} penilaian di periode \"{$periode->name}\". Semua mapping kembali ke status belum dinilai.");
    }
}
