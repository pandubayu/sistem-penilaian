@extends('layouts.app')

@section('title', 'Kriteria Teknis')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Kriteria Teknis</h1>
    <a href="{{ route('kriteria-teknis.create', ['division_id' => $selectedDivisionId]) }}"
       class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        + Tambah Kriteria
    </a>
</div>

{{-- Filter bagian (tab style) --}}
<div class="flex gap-2 mb-4 flex-wrap">
    @foreach($divisions as $division)
        <a href="{{ route('kriteria-teknis.index', ['division_id' => $division->id]) }}"
           class="px-3 py-1.5 rounded text-sm {{ $selectedDivisionId == $division->id ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            {{ $division->name }}
        </a>
    @endforeach
</div>

@if($criteria->isEmpty())
    <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded p-4 mb-4">
        Bagian ini belum memiliki kriteria teknis. Penilai tidak bisa mengisi Halaman 2 untuk karyawan di bagian ini sampai kriteria ditambahkan.
    </div>
@endif

<div class="space-y-3">
    @foreach($criteria as $item)
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-slate-800">{{ $item->order_number }}. {{ $item->aspect_name }}</h3>
                <div class="space-x-2 text-sm flex-shrink-0">
                    <a href="{{ route('kriteria-teknis.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('kriteria-teknis.destroy', $item) }}" class="inline"
                          x-data @submit.prevent="if(confirm('Hapus kriteria {{ $item->aspect_name }}?')) $el.submit()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                <div class="bg-red-50 rounded p-2">
                    <span class="font-medium text-red-700">1 - Tidak Memuaskan:</span>
                    <span class="text-slate-600">{{ $item->indicator_1 }}</span>
                </div>
                <div class="bg-amber-50 rounded p-2">
                    <span class="font-medium text-amber-700">2 - Perlu Peningkatan:</span>
                    <span class="text-slate-600">{{ $item->indicator_2 }}</span>
                </div>
                <div class="bg-blue-50 rounded p-2">
                    <span class="font-medium text-blue-700">3 - Cukup Memuaskan:</span>
                    <span class="text-slate-600">{{ $item->indicator_3 }}</span>
                </div>
                <div class="bg-green-50 rounded p-2">
                    <span class="font-medium text-green-700">4 - Sesuai Harapan:</span>
                    <span class="text-slate-600">{{ $item->indicator_4 }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
