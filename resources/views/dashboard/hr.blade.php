@extends('layouts.app')

@section('title', 'Dashboard HR')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Dashboard HR</h1>
<p class="text-sm text-slate-500 mb-6">
    @if($activePeriod)
        Periode aktif: <span class="font-medium text-slate-700">{{ $activePeriod->name }}</span>
        ({{ $activePeriod->date_range }})
    @else
        <span class="text-red-600">Belum ada periode aktif. Aktifkan periode di menu Periode.</span>
    @endif
</p>

{{-- Cards statistik --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Total Karyawan Aktif</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalEmployees }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Total Mapping</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalMappings }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Sudah Dinilai</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $doneMappings }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
        <p class="text-xs text-slate-500 uppercase">Belum Dinilai</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $belumMappings }}</p>
    </div>
</div>

{{-- Progress bar --}}
@if($totalMappings > 0)
<div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200 mb-6">
    <div class="flex justify-between text-sm mb-2">
        <span class="text-slate-600">Progres Penilaian Periode Ini</span>
        <span class="font-medium text-slate-800">{{ $doneMappings }} / {{ $totalMappings }} ({{ round(($doneMappings / $totalMappings) * 100) }}%)</span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-3">
        <div class="bg-green-500 h-3 rounded-full" style="width: {{ round(($doneMappings / $totalMappings) * 100) }}%"></div>
    </div>
</div>
@endif

{{-- Chart rata-rata per bagian --}}
<div class="bg-white rounded-lg shadow-sm p-4 border border-slate-200">
    <h2 class="text-sm font-semibold text-slate-700 mb-3">Rata-rata Nilai per Bagian</h2>

    @if($avgPerDivision->isEmpty())
        <p class="text-sm text-slate-400">Belum ada data penilaian untuk periode ini.</p>
    @else
        <canvas id="chartAvgDivision" height="100"></canvas>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('chartAvgDivision'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($avgPerDivision->pluck('division')) !!},
                    datasets: [{
                        label: 'Rata-rata Nilai',
                        data: {!! json_encode($avgPerDivision->pluck('average')) !!},
                        backgroundColor: '#475569'
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true, max: 4 } }
                }
            });
        </script>
    @endif
</div>
@endsection
