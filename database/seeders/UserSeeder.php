<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun HR (tanpa data employee, khusus admin)
        User::create([
            'name' => 'Admin HRD',
            'email' => 'hr@masterbatch.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
            'employee_id' => null,
        ]);

        $kaShift = Division::where('name', 'Ka. Shift')->first();
        $opMixer = Division::where('name', 'Operator Mixer')->first();
        $opExtruder = Division::where('name', 'Operator Extruder')->first();
        $staffQc = Division::where('name', 'Staff QC')->first();

        $employees = [
            // Level 2 - Ka. Bagian (jadi atasan)
            ['nik' => 'EMP001', 'name' => 'Budi Santoso', 'division_id' => $kaShift->id, 'level' => 2, 'contract_status' => 'Tetap'],

            // Level 1 - bawahan Operator Mixer
            ['nik' => 'EMP002', 'name' => 'Andi Wijaya', 'division_id' => $opMixer->id, 'level' => 1, 'contract_status' => 'Tetap'],
            ['nik' => 'EMP003', 'name' => 'Citra Lestari', 'division_id' => $opMixer->id, 'level' => 1, 'contract_status' => 'Kontrak'],
            ['nik' => 'EMP004', 'name' => 'Dedi Kurniawan', 'division_id' => $opMixer->id, 'level' => 1, 'contract_status' => 'Probation'],

            // Level 1 - bawahan Operator Extruder
            ['nik' => 'EMP005', 'name' => 'Eka Putri', 'division_id' => $opExtruder->id, 'level' => 1, 'contract_status' => 'Tetap'],
            ['nik' => 'EMP006', 'name' => 'Fajar Ramadhan', 'division_id' => $opExtruder->id, 'level' => 1, 'contract_status' => 'Kontrak'],
            ['nik' => 'EMP007', 'name' => 'Gita Permata', 'division_id' => $opExtruder->id, 'level' => 1, 'contract_status' => 'Magang'],

            // Level 1 - Staff QC
            ['nik' => 'EMP008', 'name' => 'Hendra Saputra', 'division_id' => $staffQc->id, 'level' => 1, 'contract_status' => 'Tetap'],
            ['nik' => 'EMP009', 'name' => 'Indah Permatasari', 'division_id' => $staffQc->id, 'level' => 1, 'contract_status' => 'Kontrak'],

            // Level 2 - Ka. lainnya untuk variasi
            ['nik' => 'EMP010', 'name' => 'Joko Susilo', 'division_id' => $staffQc->id, 'level' => 2, 'contract_status' => 'Tetap'],
        ];

        foreach ($employees as $index => $emp) {
            $employee = Employee::create([
                'nik' => $emp['nik'],
                'name' => $emp['name'],
                'division_id' => $emp['division_id'],
                'level' => $emp['level'],
                'contract_status' => $emp['contract_status'],
                'is_active' => true,
            ]);

            // Buat akun login untuk setiap karyawan
            // Role: Level 2 (Ka. Bagian) = penilai, Level 1 = karyawan
            $role = $emp['level'] == 2 ? 'penilai' : 'karyawan';

            User::create([
                'name' => $emp['name'],
                'email' => strtolower($emp['nik']) . '@masterbatch.com',
                'password' => Hash::make('password'),
                'role' => $role,
                'employee_id' => $employee->id,
            ]);
        }

        // Tambahan: jadikan beberapa Level 1 sebagai "rekan penilai" juga
        // EMP003 (Citra) dan EMP006 (Fajar) ditugaskan jadi penilai rekan
        User::where('email', 'emp003@masterbatch.com')->update(['role' => 'penilai']);
        User::where('email', 'emp006@masterbatch.com')->update(['role' => 'penilai']);
    }
}
