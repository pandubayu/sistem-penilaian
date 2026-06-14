<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssessmentReportExport implements FromCollection, WithHeadings, WithStyles
{
    public function __construct(private Collection $data)
    {
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item) {
            return [
                'rank' => $item->rank,
                'nik' => $item->employee->nik,
                'name' => $item->employee->name,
                'division' => $item->employee->division->name,
                'level' => $item->employee->level_label,
                'jumlah_penilai' => $item->jumlah_penilai,
                'total_score' => $item->total_score,
                'average_score' => $item->average_score,
                'grade' => $item->grade ?? '-',
                'reward' => $item->reward_text ?? '-',
                'punishment' => $item->punishment_text ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Peringkat',
            'NIK',
            'Nama Karyawan',
            'Bagian',
            'Level',
            'Jumlah Penilai',
            'Total Nilai',
            'Rata-rata',
            'Grade',
            'Reward',
            'Punishment',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
