<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\GeneralCriteria;
use Illuminate\Http\Request;

class GeneralCriteriaController extends Controller
{
    public function index()
    {
        $criteria = GeneralCriteria::ordered()->get();

        return view('master.kriteria-umum.index', [
            'criteria' => $criteria,
        ]);
    }

    public function create()
    {
        $maxOrder = GeneralCriteria::max('order_number') ?? 0;

        return view('master.kriteria-umum.create', [
            'nextOrder' => $maxOrder + 1,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aspect_name' => ['required', 'string', 'max:255'],
            'order_number' => ['required', 'integer', 'min:1'],
        ]);

        $criteria = GeneralCriteria::create($validated);

        ActivityLog::record('create_general_criteria', $criteria, null, $criteria->toArray());

        return redirect()->route('kriteria-umum.index')->with('success', 'Kriteria umum berhasil ditambahkan.');
    }

    public function edit(GeneralCriteria $kriteriaUmum)
    {
        return view('master.kriteria-umum.edit', [
            'criteria' => $kriteriaUmum,
        ]);
    }

    public function update(Request $request, GeneralCriteria $kriteriaUmum)
    {
        $validated = $request->validate([
            'aspect_name' => ['required', 'string', 'max:255'],
            'order_number' => ['required', 'integer', 'min:1'],
        ]);

        $oldData = $kriteriaUmum->toArray();

        $kriteriaUmum->update($validated);

        ActivityLog::record('update_general_criteria', $kriteriaUmum, $oldData, $kriteriaUmum->toArray());

        return redirect()->route('kriteria-umum.index')->with('success', 'Kriteria umum berhasil diperbarui.');
    }

    public function destroy(GeneralCriteria $kriteriaUmum)
    {
        $oldData = $kriteriaUmum->toArray();

        if ($kriteriaUmum->generalScores()->exists()) {
            return redirect()->route('kriteria-umum.index')
                ->with('error', 'Kriteria tidak bisa dihapus karena sudah dipakai dalam penilaian yang ada.');
        }

        $kriteriaUmum->delete();

        ActivityLog::record('delete_general_criteria', null, $oldData, null);

        return redirect()->route('kriteria-umum.index')->with('success', 'Kriteria umum berhasil dihapus.');
    }
}
