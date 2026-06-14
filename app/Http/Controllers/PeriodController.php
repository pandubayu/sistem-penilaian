<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderByDesc('start_date')->paginate(15);

        return view('master.periode.index', [
            'periods' => $periods,
        ]);
    }

    public function create()
    {
        return view('master.periode.create', [
            'types' => $this->periodTypes(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in($this->periodTypes())],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $period = Period::create([
            ...$validated,
            'is_active' => false,
        ]);

        ActivityLog::record('create_period', $period, null, $period->toArray());

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(Period $periode)
    {
        return view('master.periode.edit', [
            'period' => $periode,
            'types' => $this->periodTypes(),
        ]);
    }

    public function update(Request $request, Period $periode)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in($this->periodTypes())],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $oldData = $periode->toArray();

        $periode->update($validated);

        ActivityLog::record('update_period', $periode, $oldData, $periode->toArray());

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Period $periode)
    {
        $oldData = $periode->toArray();

        if ($periode->mappings()->exists() || $periode->assessments()->exists()) {
            return redirect()->route('periode.index')
                ->with('error', 'Periode tidak bisa dihapus karena sudah memiliki data mapping/penilaian.');
        }

        $periode->delete();

        ActivityLog::record('delete_period', null, $oldData, null);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus.');
    }

    /**
     * Aktifkan periode ini, otomatis non-aktifkan periode lain.
     * Hanya boleh ada SATU periode aktif dalam satu waktu.
     */
    public function activate(Period $periode)
    {
        $oldActive = Period::active()->first();

        Period::where('is_active', true)->update(['is_active' => false]);
        $periode->update(['is_active' => true]);

        ActivityLog::record(
            'activate_period',
            $periode,
            $oldActive ? ['previous_active' => $oldActive->name] : null,
            ['new_active' => $periode->name]
        );

        return redirect()->route('periode.index')->with('success', "Periode \"{$periode->name}\" sekarang aktif.");
    }

    /**
     * Daftar tipe periode yang valid (sesuai enum di migration)
     */
    private function periodTypes(): array
    {
        return ['Q1', 'Q2', 'Q3', 'Q4', 'Training 1', 'Training 2', 'Training 3', 'Percobaan 1', 'Percobaan 2', 'Percobaan 3', 'Extra Percobaan'];
    }
}
