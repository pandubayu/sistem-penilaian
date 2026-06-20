@extends('layouts.app')

@section('title', 'Penilaian - Pilih Karyawan')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Form Penilaian - Halaman 1</h1>
<p class="text-sm text-slate-500 mb-6">
    Periode aktif: <span class="font-medium text-slate-700">{{ $activePeriod->name }}</span>
    ({{ $activePeriod->date_range }})
</p>

@if($mappings->isEmpty())
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded p-4">
        🎉 Semua karyawan yang menjadi tugas Anda sudah selesai dinilai untuk periode ini.
    </div>
@else
    <p class="text-sm text-slate-600 mb-4">
        Pilih karyawan yang akan Anda nilai. Setelah dipilih, Anda akan diarahkan ke form kriteria teknis (Halaman 2).
    </p>

    <div class="space-y-3">
        @foreach($mappings as $mapping)
            <form method="POST" action="{{ route('penilaian.store-step1') }}">
                @csrf
                <input type="hidden" name="mapping_id" value="{{ $mapping->id }}">

                <button type="submit" class="w-full text-left bg-white rounded-lg shadow-sm border border-slate-200 p-4 hover:border-slate-400 hover:shadow transition flex justify-between items-center">
                    <div>
                        <p class="font-medium text-slate-800">{{ $mapping->employee->name }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $mapping->employee->division->name }} &middot; {{ $mapping->employee->level_label }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($mapping->assessor_type == 'atasan')
                            <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">Anda sebagai Atasan</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-slate-100 text-slate-600">Anda sebagai Rekan</span>
                        @endif
                        <p class="text-xs text-slate-400 mt-1">Klik untuk mulai menilai →</p>
                    </div>
                </button>
            </form>
        @endforeach
    </div>
@endif
@endsection
