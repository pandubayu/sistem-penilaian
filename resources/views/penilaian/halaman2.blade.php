@extends('layouts.app')

@section('title', 'Penilaian - Kriteria Teknis')

@section('content')
{{-- Progress indicator --}}
<div class="flex items-center gap-2 mb-6 text-sm">
    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-500">1. Pilih Karyawan</span>
    <span class="text-slate-300">→</span>
    <span class="px-3 py-1 rounded-full bg-slate-800 text-white font-medium">2. Kriteria Teknis</span>
    <span class="text-slate-300">→</span>
    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-500">3. Kriteria Umum</span>
</div>

<h1 class="text-2xl font-bold text-slate-800 mb-1">Kriteria Teknis</h1>
<p class="text-sm text-slate-500 mb-6">
    Menilai: <span class="font-medium text-slate-700">{{ $mapping->employee->name }}</span>
    &middot; {{ $mapping->employee->division->name }} &middot; {{ $mapping->employee->level_label }}
</p>

<div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded p-3 mb-6">
    Skala nilai: <strong>1</strong> = Tidak Memuaskan, <strong>2</strong> = Perlu Peningkatan, <strong>3</strong> = Cukup Memuaskan, <strong>4</strong> = Sesuai Harapan
</div>

<form method="POST" action="{{ route('penilaian.store-step2', $mapping->id) }}">
    @csrf

    <div class="space-y-4">
        @foreach($technicalCriteria as $criteria)
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <p class="font-semibold text-slate-800 mb-3">{{ $criteria->order_number }}. {{ $criteria->aspect_name }}</p>

                <div class="space-y-2" x-data="{ selected: '{{ old('technical.' . $criteria->id) }}' }">
                    @foreach($criteria->indicators as $score => $description)
                        <label class="flex items-start gap-3 p-3 rounded border cursor-pointer transition"
                               :class="selected == '{{ $score }}' ? 'border-slate-800 bg-slate-50' : 'border-slate-200 hover:bg-slate-50'">
                            <input type="radio" name="technical[{{ $criteria->id }}]" value="{{ $score }}"
                                   x-model="selected" required class="mt-1">
                            <span class="text-sm">
                                <span class="font-medium text-slate-700">{{ $score }} - {{ $scaleLabels[$score] }}:</span>
                                <span class="text-slate-600">{{ $description }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between mt-6">
        <a href="{{ route('penilaian.create') }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">
            ← Kembali
        </a>
        <button type="submit" class="bg-slate-800 text-white text-sm px-6 py-2 rounded hover:bg-slate-700">
            Lanjut ke Kriteria Umum →
        </button>
    </div>
</form>
@endsection
