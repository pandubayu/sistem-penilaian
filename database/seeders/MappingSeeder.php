<?php

namespace Database\Seeders;

use App\Models\AssessorMapping;
use App\Models\Employee;
use App\Models\Period;
use Illuminate\Database\Seeder;

class MappingSeeder extends Seeder
{
    public function run(): void
    {
        $period = Period::where('type', 'Q1')->first();

        // Ambil employee berdasarkan NIK untuk kemudahan mapping
        $emp = Employee::pluck('id', 'nik');

        // Budi Santoso (EMP001, Ka. Shift, Level 2) = atasan untuk Operator Mixer & Extruder
        // Joko Susilo (EMP010, Ka. QC, Level 2) = atasan untuk Staff QC

        $mappings = [
            // ===== Operator Mixer (atasan: Budi/EMP001) =====
            // Andi Wijaya (EMP002) dinilai oleh: Budi (atasan), Citra (rekan), Dedi (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP002', 'type' => 'atasan'],
            ['assessor' => 'EMP003', 'employee' => 'EMP002', 'type' => 'rekan'],
            ['assessor' => 'EMP004', 'employee' => 'EMP002', 'type' => 'rekan'],

            // Citra Lestari (EMP003) dinilai oleh: Budi (atasan), Andi (rekan), Dedi (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP003', 'type' => 'atasan'],
            ['assessor' => 'EMP002', 'employee' => 'EMP003', 'type' => 'rekan'],
            ['assessor' => 'EMP004', 'employee' => 'EMP003', 'type' => 'rekan'],

            // Dedi Kurniawan (EMP004) dinilai oleh: Budi (atasan), Andi (rekan), Citra (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP004', 'type' => 'atasan'],
            ['assessor' => 'EMP002', 'employee' => 'EMP004', 'type' => 'rekan'],
            ['assessor' => 'EMP003', 'employee' => 'EMP004', 'type' => 'rekan'],

            // ===== Operator Extruder (atasan: Budi/EMP001) =====
            // Eka Putri (EMP005) dinilai oleh: Budi (atasan), Fajar (rekan), Gita (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP005', 'type' => 'atasan'],
            ['assessor' => 'EMP006', 'employee' => 'EMP005', 'type' => 'rekan'],
            ['assessor' => 'EMP007', 'employee' => 'EMP005', 'type' => 'rekan'],

            // Fajar Ramadhan (EMP006) dinilai oleh: Budi (atasan), Eka (rekan), Gita (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP006', 'type' => 'atasan'],
            ['assessor' => 'EMP005', 'employee' => 'EMP006', 'type' => 'rekan'],
            ['assessor' => 'EMP007', 'employee' => 'EMP006', 'type' => 'rekan'],

            // Gita Permata (EMP007) dinilai oleh: Budi (atasan), Eka (rekan), Fajar (rekan)
            ['assessor' => 'EMP001', 'employee' => 'EMP007', 'type' => 'atasan'],
            ['assessor' => 'EMP005', 'employee' => 'EMP007', 'type' => 'rekan'],
            ['assessor' => 'EMP006', 'employee' => 'EMP007', 'type' => 'rekan'],

            // ===== Staff QC (atasan: Joko/EMP010) =====
            // Hendra Saputra (EMP008) dinilai oleh: Joko (atasan), Indah (rekan)
            ['assessor' => 'EMP010', 'employee' => 'EMP008', 'type' => 'atasan'],
            ['assessor' => 'EMP009', 'employee' => 'EMP008', 'type' => 'rekan'],

            // Indah Permatasari (EMP009) dinilai oleh: Joko (atasan), Hendra (rekan)
            ['assessor' => 'EMP010', 'employee' => 'EMP009', 'type' => 'atasan'],
            ['assessor' => 'EMP008', 'employee' => 'EMP009', 'type' => 'rekan'],

            // ===== Budi Santoso (EMP001, Ka. Shift) dinilai oleh Joko (rekan Ka. lain) =====
            ['assessor' => 'EMP010', 'employee' => 'EMP001', 'type' => 'rekan'],

            // ===== Joko Susilo (EMP010, Ka. QC) dinilai oleh Budi (rekan Ka. lain) =====
            ['assessor' => 'EMP001', 'employee' => 'EMP010', 'type' => 'rekan'],
        ];

        foreach ($mappings as $map) {
            AssessorMapping::create([
                'period_id' => $period->id,
                'assessor_id' => $emp[$map['assessor']],
                'employee_id' => $emp[$map['employee']],
                'assessor_type' => $map['type'],
                'is_done' => false,
            ]);
        }
    }
}
