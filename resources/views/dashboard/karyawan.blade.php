@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Halo, {{ $employee?->name ?? auth()->user()->name }}</h1>
<p class="text-sm text-slate-500 mb-6">
    @if($employee)
        {{ $employee->division->name }} &middot; {{ $employee->level_label }} &middot; {{ $employee->contract_status }}
    @else
        Akun Anda belum terhubung dengan data karyawan. Hubungi HR.
    @endif
</p>

{{-- Riwayat penilaian --}}
<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-100">
        <h2 class="text-sm font-semibold text-slate-700">Riwayat Hasil Penilaian Saya</h2>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Periode</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Dinilai Oleh</th>
                <th class="px-4 py-3">Total Nilai</th>
                <th class="px-4 py-3">Rata-rata</th>
                <th class="px-4 py-3">Grade</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($assessments as $assessment)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $assessment->period->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $assessment->assessment_date->translatedFormat('d M Y') }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $assessment->assessor->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $assessment->total_score }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $assessment->average_score }}</td>
                    <td class="px-4 py-3">
                        @if($assessment->grade)
                            <span class="px-2 py-1 rounded text-xs {{ $assessment->grade_badge }}">{{ $assessment->grade }}</span>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                        Belum ada hasil penilaian untuk Anda.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($assessments->isNotEmpty())
<p class="text-xs text-slate-400 mt-3">
    Catatan: nilai yang ditampilkan adalah dari masing-masing penilai (atasan/rekan) secara terpisah.
    Untuk hasil akhir gabungan (rata-rata dari semua penilai) beserta grade resmi, lihat Laporan Raport dari HR.
</p>
@endif
@endsection
