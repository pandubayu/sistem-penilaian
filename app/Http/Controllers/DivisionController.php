<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::withCount(['employees', 'technicalCriteria'])
            ->orderBy('name')
            ->paginate(15);

        return view('master.bagian.index', [
            'divisions' => $divisions,
        ]);
    }

    public function create()
    {
        return view('master.bagian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:divisions,name'],
            'description' => ['nullable', 'string'],
        ]);

        $division = Division::create($validated);

        ActivityLog::record('create_division', $division, null, $division->toArray());

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil ditambahkan.');
    }

    public function edit(Division $bagian)
    {
        return view('master.bagian.edit', [
            'division' => $bagian,
        ]);
    }

    public function update(Request $request, Division $bagian)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('divisions', 'name')->ignore($bagian->id)],
            'description' => ['nullable', 'string'],
        ]);

        $oldData = $bagian->toArray();

        $bagian->update($validated);

        ActivityLog::record('update_division', $bagian, $oldData, $bagian->toArray());

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil diperbarui.');
    }

    public function destroy(Division $bagian)
    {
        $oldData = $bagian->toArray();

        if ($bagian->employees()->exists()) {
            return redirect()->route('bagian.index')
                ->with('error', 'Bagian tidak bisa dihapus karena masih memiliki karyawan terdaftar.');
        }

        $bagian->delete();

        ActivityLog::record('delete_division', null, $oldData, null);

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil dihapus.');
    }
}
