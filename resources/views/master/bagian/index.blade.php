@extends('layouts.app')

@section('title', 'Data Bagian')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Data Bagian</h1>
    <a href="{{ route('bagian.create') }}" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        + Tambah Bagian
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Nama Bagian</th>
                <th class="px-4 py-3">Deskripsi</th>
                <th class="px-4 py-3">Jumlah Karyawan</th>
                <th class="px-4 py-3">Jumlah Kriteria Teknis</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($divisions as $division)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $division->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $division->description ?: '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $division->employees_count }}</td>
                    <td class="px-4 py-3 text-slate-600">
                        {{ $division->technical_criteria_count }}
                        @if($division->technical_criteria_count == 0)
                            <span class="text-amber-600 text-xs">(belum ada)</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('kriteria-teknis.index', ['division_id' => $division->id]) }}" class="text-slate-600 hover:underline">Kriteria</a>
                        <a href="{{ route('bagian.edit', $division) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('bagian.destroy', $division) }}" class="inline"
                              x-data @submit.prevent="if(confirm('Hapus bagian {{ $division->name }}?')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-400">Tidak ada data bagian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $divisions->links() }}
</div>
@endsection
