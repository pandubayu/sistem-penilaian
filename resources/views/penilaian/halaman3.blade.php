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

    {{-- 17 Kriteria Umum --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left w-12">No</th>
                    <th class="px-4 py-3 text-left">Aspek</th>
                    @foreach($scaleLabels as $score => $label)
                        <th class="px-2 py-3 text-center w-24">{{ $score }}<br><span class="text-xs font-normal">{{ $label }}</span></th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($generalCriteria as $criteria)
                    <tr>
                        <td class="px-4 py-3 text-slate-600">{{ $criteria->order_number }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $criteria->aspect_name }}</td>
                        @foreach($scaleLabels as $score => $label)
                            <td class="px-2 py-3 text-center">
                                <input type="radio" name="general[{{ $criteria->id }}]" value="{{ $score }}" required
                                       class="w-4 h-4">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
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
