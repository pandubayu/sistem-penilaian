<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::with(['division', 'user'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                });
            })
            ->when($request->division_id, function ($q, $divisionId) {
                $q->where('division_id', $divisionId);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('master.karyawan.index', [
            'employees' => $employees,
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('master.karyawan.create', [
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:20', 'unique:employees,nik'],
            'name' => ['required', 'string', 'max:255'],
            'division_id' => ['required', 'exists:divisions,id'],
            'level' => ['required', 'integer', 'in:1,2'],
            'contract_status' => ['required', Rule::in(['Tetap', 'Kontrak', 'Probation', 'Magang'])],
            'is_active' => ['nullable', 'boolean'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['hr', 'penilai', 'karyawan'])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $employee = Employee::create([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'division_id' => $validated['division_id'],
            'level' => $validated['level'],
            'contract_status' => $validated['contract_status'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'employee_id' => $employee->id,
        ]);

        ActivityLog::record('create_employee', $employee, null, $employee->toArray());

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Employee $karyawan)
    {
        $karyawan->load('user');

        return view('master.karyawan.edit', [
            'employee' => $karyawan,
            'divisions' => Division::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Employee $karyawan)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:20', Rule::unique('employees', 'nik')->ignore($karyawan->id)],
            'name' => ['required', 'string', 'max:255'],
            'division_id' => ['required', 'exists:divisions,id'],
            'level' => ['required', 'integer', 'in:1,2'],
            'contract_status' => ['required', Rule::in(['Tetap', 'Kontrak', 'Probation', 'Magang'])],
            'is_active' => ['nullable', 'boolean'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($karyawan->user?->id)],
            'role' => ['required', Rule::in(['hr', 'penilai', 'karyawan'])],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $oldData = $karyawan->toArray();

        $karyawan->update([
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'division_id' => $validated['division_id'],
            'level' => $validated['level'],
            'contract_status' => $validated['contract_status'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($karyawan->user) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $karyawan->user->update($userData);
        } else {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'password'),
                'role' => $validated['role'],
                'employee_id' => $karyawan->id,
            ]);
        }

        ActivityLog::record('update_employee', $karyawan, $oldData, $karyawan->toArray());

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $karyawan)
    {
        $oldData = $karyawan->toArray();

        // Cek apakah karyawan masih punya mapping atau assessment terkait
        if ($karyawan->mappingsAsEmployee()->exists() || $karyawan->mappingsAsAssessor()->exists()) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Karyawan tidak bisa dihapus karena masih terdaftar di mapping penilaian. Nonaktifkan saja.');
        }

        $karyawan->user?->delete();
        $karyawan->delete();

        ActivityLog::record('delete_employee', null, $oldData, null);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
