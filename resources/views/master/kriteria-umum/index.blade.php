@extends('layouts.app')

@section('title', 'Kriteria Umum')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Kriteria Umum (17 Aspek)</h1>
    <a href="{{ route('kriteria-umum.create') }}" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        + Tambah Kriteria
    </a>
</div>

<p class="text-sm text-slate-500 mb-4">
    Kriteria umum berlaku untuk <strong>semua karyawan</strong> tanpa terkecuali, dan ditampilkan di Halaman 3 form penilaian.
</p>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3 w-16">Urutan</th>
                <th class="px-4 py-3">Nama Aspek</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($criteria as $item)
                <tr>
                    <td class="px-4 py-3 text-slate-600">{{ $item->order_number }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $item->aspect_name }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('kriteria-umum.edit', $item) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('kriteria-umum.destroy', $item) }}" class="inline"
                              x-data @submit.prevent="if(confirm('Hapus kriteria {{ $item->aspect_name }}?')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-slate-400">Tidak ada data kriteria umum.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($criteria->count() != 17)
    <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded p-4">
        Jumlah kriteria umum saat ini: <strong>{{ $criteria->count() }}</strong>. Sesuai standar perusahaan seharusnya ada <strong>17 aspek</strong>.
    </div>
@endif
@endsection
