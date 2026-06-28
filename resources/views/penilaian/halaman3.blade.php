@extends('layouts.app')

@section('title', 'Penilaian - Kriteria Umum')

@section('content')
{{-- Progress indicator --}}
<div class="flex items-center gap-2 mb-6 text-sm">
    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-500">1. Pilih Karyawan</span>
    <span class="text-slate-300">→</span>
    <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-500">2. Kriteria Teknis</span>
    <span class="text-slate-300">→</span>
    <span class="px-3 py-1 rounded-full bg-slate-800 text-white font-medium">3. Kriteria Umum</span>
</div>

<h1 class="text-2xl font-bold text-slate-800 mb-1">Kriteria Umum (17 Aspek)</h1>
<p class="text-sm text-slate-500 mb-6">
    Menilai: <span class="font-medium text-slate-700">{{ $mapping->employee->name }}</span>
    &middot; {{ $mapping->employee->division->name }}
</p>

<div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded p-3 mb-6">
    Skala nilai: <strong>1</strong> = Tidak Memuaskan, <strong>2</strong> = Perlu Peningkatan, <strong>3</strong> = Cukup Memuaskan, <strong>4</strong> = Sesuai Harapan
</div>

<form method="POST" action="{{ route('penilaian.store-step3', $mapping->id) }}"
      x-data="{ submitting: false }" @submit="submitting = true">
    @csrf

    {{-- Nama Penilai (auto-filled, read-only) --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Penilai</label>
        <input type="text" value="{{ $mapping->assessor->name }}" disabled
               class="w-full border border-slate-200 bg-slate-50 rounded px-3 py-2 text-sm text-slate-500">
        <p class="text-xs text-slate-400 mt-1">Otomatis terisi sesuai akun yang sedang login.</p>
    </div>

    {{-- 17 Kriteria Umum — format card seperti halaman 2 --}}
    <div class="space-y-4 mb-4">
        @foreach($generalCriteria as $criteria)
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <p class="font-semibold text-slate-800 mb-3">
                    {{ $criteria->order_number }}. {{ $criteria->aspect_name }}
                </p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-2"
                     x-data="{ selected: '{{ old('general.' . $criteria->id) }}' }">
                    @foreach($scaleLabels as $score => $label)
                        @php
                            $colors = [
                                1 => ['border' => 'border-red-300',    'bg' => 'bg-red-50',    'text' => 'text-red-700',    'ring' => 'ring-red-400'],
                                2 => ['border' => 'border-amber-300',  'bg' => 'bg-amber-50',  'text' => 'text-amber-700',  'ring' => 'ring-amber-400'],
                                3 => ['border' => 'border-blue-300',   'bg' => 'bg-blue-50',   'text' => 'text-blue-700',   'ring' => 'ring-blue-400'],
                                4 => ['border' => 'border-green-300',  'bg' => 'bg-green-50',  'text' => 'text-green-700',  'ring' => 'ring-green-400'],
                            ];
                            $c = $colors[$score];
                        @endphp

                        <label class="relative flex flex-col items-center justify-center gap-1 p-3 rounded-lg border-2 cursor-pointer transition-all text-center"
                               :class="selected == '{{ $score }}'
                                   ? '{{ $c['border'] }} {{ $c['bg'] }} ring-2 {{ $c['ring'] }}'
                                   : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">

                            <input type="radio"
                                   name="general[{{ $criteria->id }}]"
                                   value="{{ $score }}"
                                   x-model="selected"
                                   required
                                   class="sr-only">

                            {{-- Angka nilai --}}
                            <span class="text-2xl font-bold"
                                  :class="selected == '{{ $score }}' ? '{{ $c['text'] }}' : 'text-slate-400'">
                                {{ $score }}
                            </span>

                            {{-- Label nilai --}}
                            <span class="text-xs font-medium leading-tight"
                                  :class="selected == '{{ $score }}' ? '{{ $c['text'] }}' : 'text-slate-500'">
                                {{ $label }}
                            </span>

                            {{-- Centang kalau dipilih --}}
                            <span x-show="selected == '{{ $score }}'"
                                  class="absolute top-1.5 right-1.5 text-xs {{ $c['text'] }}">
                                ✓
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Catatan tambahan --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 mb-4">
        <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Tambahan (opsional)</label>
        <textarea name="notes" rows="3" placeholder="Masukan, saran, atau catatan khusus untuk karyawan ini..."
                  class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('notes') }}</textarea>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('penilaian.step2', $mapping->id) }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">
            ← Kembali
        </a>
        <button type="submit" :disabled="submitting"
                class="bg-green-600 text-white text-sm px-6 py-2 rounded hover:bg-green-700 disabled:opacity-50"
                @click="if(!confirm('Pastikan semua jawaban sudah benar. Penilaian tidak bisa diubah setelah disimpan. Lanjutkan?')) { $event.preventDefault(); submitting = false; }">
            <span x-show="!submitting">Simpan Penilaian</span>
            <span x-show="submitting">Menyimpan...</span>
        </button>
    </div>
</form>
@endsection
