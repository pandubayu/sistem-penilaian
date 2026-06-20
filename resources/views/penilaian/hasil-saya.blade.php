@extends('layouts.app')

@section('title', 'Hasil Penilaian Saya')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Hasil Penilaian Saya</h1>
<p class="text-sm text-slate-500 mb-6">
    {{ $employee->name }} &middot; {{ $employee->division->name }} &middot; {{ $employee->level_label }}
</p>

@if($assessments->isEmpty())
    <div class="bg-slate-50 border border-slate-200 text-slate-500 text-sm rounded p-4">
        Belum ada hasil penilaian untuk Anda.
    </div>
@else
    <div class="space-y-6">
        @foreach($assessments as $assessment)
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $assessment->period->name }}</p>
                        <p class="text-sm text-slate-500">
                            Dinilai oleh {{ $assessment->assessor->name }} &middot; {{ $assessment->assessment_date->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-slate-800">{{ $assessment->total_score }}</p>
                        <p class="text-xs text-slate-400">Total Nilai</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">Kriteria Teknis</p>
                        <ul class="text-sm space-y-1">
                            @foreach($assessment->technicalScores as $score)
                                <li class="flex justify-between border-b border-slate-100 pb-1">
                                    <span class="text-slate-600">{{ $score->criteria->aspect_name }}</span>
                                    <span class="font-medium text-slate-800">{{ $score->score }} - {{ $score->score_label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-2">Kriteria Umum</p>
                        <ul class="text-sm space-y-1 max-h-64 overflow-y-auto">
                            @foreach($assessment->generalScores as $score)
                                <li class="flex justify-between border-b border-slate-100 pb-1">
                                    <span class="text-slate-600">{{ $score->criteria->aspect_name }}</span>
                                    <span class="font-medium text-slate-800">{{ $score->score }} - {{ $score->score_label }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @if($assessment->notes)
                    <div class="mt-4 bg-slate-50 rounded p-3 text-sm">
                        <p class="font-medium text-slate-600 mb-1">Catatan dari Penilai:</p>
                        <p class="text-slate-700">{{ $assessment->notes }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
@endsection
