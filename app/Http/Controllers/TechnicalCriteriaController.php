<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Division;
use App\Models\TechnicalCriteria;
use Illuminate\Http\Request;

class TechnicalCriteriaController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::orderBy('name')->get();

        // Filter per bagian, default ke bagian pertama
        $selectedDivisionId = $request->division_id ?? $divisions->first()?->id;

        $criteria = TechnicalCriteria::where('division_id', $selectedDivisionId)
            ->orderBy('order_number')
            ->get();

        return view('master.kriteria-teknis.index', [
            'divisions' => $divisions,
            'selectedDivisionId' => $selectedDivisionId,
            'criteria' => $criteria,
        ]);
    }

    public function create(Request $request)
    {
        return view('master.kriteria-teknis.create', [
            'divisions' => Division::orderBy('name')->get(),
            'selectedDivisionId' => $request->division_id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'aspect_name' => ['required', 'string', 'max:255'],
            'indicator_1' => ['required', 'string'],
            'indicator_2' => ['required', 'string'],
            'indicator_3' => ['required', 'string'],
            'indicator_4' => ['required', 'string'],
            'order_number' => ['nullable', 'integer', 'min:0'],
        ]);

        // Auto-assign order_number kalau tidak diisi (taruh di urutan terakhir)
        if (empty($validated['order_number'])) {
            $maxOrder = TechnicalCriteria::where('division_id', $validated['division_id'])->max('order_number');
            $validated['order_number'] = ($maxOrder ?? 0) + 1;
        }

        $criteria = TechnicalCriteria::create($validated);

        ActivityLog::record('create_technical_criteria', $criteria, null, $criteria->toArray());

        return redirect()->route('kriteria-teknis.index', ['division_id' => $validated['division_id']])
            ->with('success', 'Kriteria teknis berhasil ditambahkan.');
    }

    public function edit(TechnicalCriteria $kriteriaTeknis)
    {
        return view('master.kriteria-teknis.edit', [
            'criteria' => $kriteriaTeknis,
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, TechnicalCriteria $kriteriaTeknis)
    {
        $validated = $request->validate([
            'division_id' => ['required', 'exists:divisions,id'],
            'aspect_name' => ['required', 'string', 'max:255'],
            'indicator_1' => ['required', 'string'],
            'indicator_2' => ['required', 'string'],
            'indicator_3' => ['required', 'string'],
            'indicator_4' => ['required', 'string'],
            'order_number' => ['nullable', 'integer', 'min:0'],
        ]);

        $oldData = $kriteriaTeknis->toArray();

        $kriteriaTeknis->update($validated);

        ActivityLog::record('update_technical_criteria', $kriteriaTeknis, $oldData, $kriteriaTeknis->toArray());

        return redirect()->route('kriteria-teknis.index', ['division_id' => $kriteriaTeknis->division_id])
            ->with('success', 'Kriteria teknis berhasil diperbarui.');
    }

    public function destroy(TechnicalCriteria $kriteriaTeknis)
    {
        $oldData = $kriteriaTeknis->toArray();
        $divisionId = $kriteriaTeknis->division_id;

        // Cek apakah kriteria ini sudah pernah dipakai di penilaian
        if ($kriteriaTeknis->technicalScores()->exists()) {
            return redirect()->route('kriteria-teknis.index', ['division_id' => $divisionId])
                ->with('error', 'Kriteria tidak bisa dihapus karena sudah dipakai dalam penilaian yang ada.');
        }

        $kriteriaTeknis->delete();

        ActivityLog::record('delete_technical_criteria', null, $oldData, null);

        return redirect()->route('kriteria-teknis.index', ['division_id' => $divisionId])
            ->with('success', 'Kriteria teknis berhasil dihapus.');
    }
}
