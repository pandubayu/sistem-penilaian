<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penilaian</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1e293b;
        }
        h1 {
            font-size: 16px;
            margin-bottom: 2px;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 16px;
        }
        h2 {
            font-size: 13px;
            margin-top: 20px;
            margin-bottom: 6px;
            background-color: #f1f5f9;
            padding: 6px 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .grade-a { background-color: #dcfce7; font-weight: bold; }
        .grade-b { background-color: #dbeafe; font-weight: bold; }
        .grade-c { background-color: #fef3c7; font-weight: bold; }
        .grade-d { background-color: #fee2e2; font-weight: bold; }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <h1>Laporan Penilaian Prestasi Kerja Karyawan</h1>
    <p class="subtitle">
        Periode: {{ $period->name ?? '-' }}
        ({{ $period ? $period->start_date->format('d M Y') . ' - ' . $period->end_date->format('d M Y') : '-' }})
        &nbsp;|&nbsp; Dicetak: {{ $generatedAt->translatedFormat('d M Y H:i') }}
    </p>

    <h2>Peringkat Level 1 — Operator/Staff</h2>
    <table>
        <thead>
            <tr>
                <th class="text-center">Peringkat</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Bagian</th>
                <th class="text-center">Jml Penilai</th>
                <th class="text-center">Total Nilai</th>
                <th class="text-center">Rata-rata</th>
                <th class="text-center">Grade</th>
                <th>Reward / Punishment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assessmentsLevel1 as $item)
                <tr>
                    <td class="text-center">#{{ $item->rank }}</td>
                    <td>{{ $item->employee->nik }}</td>
                    <td>{{ $item->employee->name }}</td>
                    <td>{{ $item->employee->division->name }}</td>
                    <td class="text-center">{{ $item->jumlah_penilai }}</td>
                    <td class="text-center">{{ $item->total_score }}</td>
                    <td class="text-center">{{ $item->average_score }}</td>
                    <td class="text-center grade-{{ strtolower($item->grade ?? '') }}">{{ $item->grade ?? '-' }}</td>
                    <td>
                        @if($item->grade == 'A' || $item->grade == 'B')
                            {{ $item->reward_text }}
                        @elseif($item->punishment_text && $item->punishment_text != '-')
                            {{ $item->punishment_text }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Peringkat Level 2 — Ka. Bagian</h2>
    <table>
        <thead>
            <tr>
                <th class="text-center">Peringkat</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Bagian</th>
                <th class="text-center">Jml Penilai</th>
                <th class="text-center">Total Nilai</th>
                <th class="text-center">Rata-rata</th>
                <th class="text-center">Grade</th>
                <th>Reward / Punishment</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assessmentsLevel2 as $item)
                <tr>
                    <td class="text-center">#{{ $item->rank }}</td>
                    <td>{{ $item->employee->nik }}</td>
                    <td>{{ $item->employee->name }}</td>
                    <td>{{ $item->employee->division->name }}</td>
                    <td class="text-center">{{ $item->jumlah_penilai }}</td>
                    <td class="text-center">{{ $item->total_score }}</td>
                    <td class="text-center">{{ $item->average_score }}</td>
                    <td class="text-center grade-{{ strtolower($item->grade ?? '') }}">{{ $item->grade ?? '-' }}</td>
                    <td>
                        @if($item->grade == 'A' || $item->grade == 'B')
                            {{ $item->reward_text }}
                        @elseif($item->punishment_text && $item->punishment_text != '-')
                            {{ $item->punishment_text }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">
        Dokumen ini dihasilkan otomatis oleh Sistem Penilaian Prestasi Kerja Karyawan.
        Total Nilai dan Rata-rata merupakan hasil gabungan (rata-rata) dari seluruh penilai (atasan + rekan) dengan bobot 1:1.
    </p>

</body>
</html>
