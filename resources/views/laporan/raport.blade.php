@extends('layouts.app')

@section('title', 'Laporan Raport')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Laporan Raport</h1>
    <div class="flex gap-2">
        <a href="{{ route('laporan.export-pdf', request()->query()) }}"
           class="bg-red-600 text-white text-sm px-4 py-2 rounded hover:bg-red-700">
            Export PDF
        </a>
        <a href="{{ route('laporan.export-excel', request()->query()) }}"
           class="bg-green-600 text-white text-sm px-4 py-2 rounded hover:bg-green-700">
            Export Excel
        </a>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="flex gap-2 mb-6">
    <select name="period_id" onchange="this.form.submit()" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        @foreach($periods as $period)
            <option value="{{ $period->id }}" @selected($selectedPeriodId == $period->id)>
                {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
            </option>
        @endforeach
    </select>

    <select name="division_id" onchange="this.form.submit()" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        <option value="">Semua Bagian</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}" @selected($selectedDivisionId == $division->id)>{{ $division->name }}</option>
        @endforeach
    </select>
</form>

{{-- LEVEL 1 --}}
<div class="mb-8">
    <h2 class="text-lg font-semibold text-slate-800 mb-3">Peringkat Level 1 — Operator/Staff</h2>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="table-responsive">
    <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3 w-16">Peringkat</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Bagian</th>
                    <th class="px-4 py-3 text-center">Jml Penilai</th>
                    <th class="px-4 py-3 text-center">Total Nilai</th>
                    <th class="px-4 py-3 text-center">Rata-rata</th>
                    <th class="px-4 py-3 text-center">Grade</th>
                    <th class="px-4 py-3">Reward / Punishment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($assessmentsLevel1 as $item)
                    <tr>
                        <td class="px-4 py-3 font-bold text-slate-700">#{{ $item->rank }}</td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $item->employee->nik }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->employee->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->employee->division->name }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $item->jumlah_penilai }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ $item->total_score }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $item->average_score }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->grade)
                                <span class="px-2 py-1 rounded text-xs font-bold {{ \App\Models\GradingThreshold::where('grade', $item->grade)->first()->grade_badge ?? '' }}">
                                    {{ $item->grade }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">
                            @if($item->grade == 'A' || $item->grade == 'B')
                                <span class="text-green-700">{{ $item->reward_text }}</span>
                            @elseif($item->punishment_text && $item->punishment_text != '-')
                                <span class="text-red-700">{{ $item->punishment_text }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-slate-400">Belum ada data penilaian Level 1 untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

{{-- LEVEL 2 --}}
<div class="mb-8">
    <h2 class="text-lg font-semibold text-slate-800 mb-3">Peringkat Level 2 — Ka. Bagian</h2>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="table-responsive">
    <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3 w-16">Peringkat</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Bagian</th>
                    <th class="px-4 py-3 text-center">Jml Penilai</th>
                    <th class="px-4 py-3 text-center">Total Nilai</th>
                    <th class="px-4 py-3 text-center">Rata-rata</th>
                    <th class="px-4 py-3 text-center">Grade</th>
                    <th class="px-4 py-3">Reward / Punishment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($assessmentsLevel2 as $item)
                    <tr>
                        <td class="px-4 py-3 font-bold text-slate-700">#{{ $item->rank }}</td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $item->employee->nik }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->employee->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->employee->division->name }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $item->jumlah_penilai }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-slate-800">{{ $item->total_score }}</td>
                        <td class="px-4 py-3 text-center text-slate-600">{{ $item->average_score }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->grade)
                                <span class="px-2 py-1 rounded text-xs font-bold {{ \App\Models\GradingThreshold::where('grade', $item->grade)->first()->grade_badge ?? '' }}">
                                    {{ $item->grade }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs">
                            @if($item->grade == 'A' || $item->grade == 'B')
                                <span class="text-green-700">{{ $item->reward_text }}</span>
                            @elseif($item->punishment_text && $item->punishment_text != '-')
                                <span class="text-red-700">{{ $item->punishment_text }}</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-slate-400">Belum ada data penilaian Level 2 untuk periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

<p class="text-xs text-slate-400">
    Catatan: "Total Nilai" dan "Rata-rata" adalah hasil gabungan (rata-rata) dari semua penilai (atasan + rekan) untuk karyawan tersebut, dengan bobot 1:1.
</p>
@endsection
