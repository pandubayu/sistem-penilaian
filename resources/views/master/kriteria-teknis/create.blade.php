@extends('layouts.app')

@section('title', 'Tambah Kriteria Teknis')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Tambah Kriteria Teknis</h1>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('kriteria-teknis.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Bagian</label>
            <select name="division_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                <option value="">-- Pilih Bagian --</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}" @selected(old('division_id', $selectedDivisionId) == $division->id)>{{ $division->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Aspek</label>
            <input type="text" name="aspect_name" value="{{ old('aspect_name') }}" required placeholder="Contoh: Penguasaan Formula Campuran"
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-red-700 mb-1">Indikator 1 - Tidak Memuaskan</label>
            <textarea name="indicator_1" rows="2" required
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('indicator_1') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-amber-700 mb-1">Indikator 2 - Perlu Peningkatan</label>
            <textarea name="indicator_2" rows="2" required
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('indicator_2') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-blue-700 mb-1">Indikator 3 - Cukup Memuaskan</label>
            <textarea name="indicator_3" rows="2" required
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('indicator_3') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-green-700 mb-1">Indikator 4 - Sesuai Harapan</label>
            <textarea name="indicator_4" rows="2" required
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('indicator_4') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Urutan Tampil (kosongkan untuk otomatis)</label>
            <input type="number" name="order_number" value="{{ old('order_number') }}" min="1"
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">Simpan</button>
            <a href="{{ route('kriteria-teknis.index', ['division_id' => $selectedDivisionId]) }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">Batal</a>
        </div>
    </form>
</div>
@endsection
