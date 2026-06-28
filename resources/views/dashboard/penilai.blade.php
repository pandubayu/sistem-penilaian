@extends('layouts.app')

@section('title', 'Dashboard Penilai')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Dashboard Penilai</h1>
<p class="text-sm text-slate-500 mb-6">
    @if($activePeriod)
        Periode aktif: <span class="font-medium text-slate-700">{{ $activePeriod->name }}</span>
        ({{ $activePeriod->date_range }})
    @else
        <span class="text-red-600">Belum ada periode aktif. Hubungi HR.</span>
    @endif
</p>

{{-- Cards statistik --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Total Tugas Penilaian</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTugas }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Sudah Dinilai</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $sudahDinilai }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Belum Dinilai</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $belumDinilai }}</p>
    </div>
</div>

@if($belumDinilai > 0)
<div class="mb-4">
    <a href="{{ route('penilaian.create') }}"
       class="inline-block bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        Mulai Isi Penilaian
    </a>
</div>
@endif

{{-- Tabel daftar karyawan yang wajib dinilai --}}
<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <div class="table-responsive">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Nama Karyawan</th>
                <th class="px-4 py-3">Bagian</th>
                <th class="px-4 py-3">Tipe Penilai</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($mappings as $mapping)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $mapping->employee->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mapping->employee->division->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mapping->assessor_type_label }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $mapping->status_badge }}">
                            {{ $mapping->status_label }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-slate-400">
                        Tidak ada tugas penilaian untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
