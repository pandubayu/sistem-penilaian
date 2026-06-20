@extends('layouts.app')

@section('title', 'Periode Penilaian')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Periode Penilaian</h1>
    <a href="{{ route('periode.create') }}" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        + Tambah Periode
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Nama Periode</th>
                <th class="px-4 py-3">Tipe</th>
                <th class="px-4 py-3">Tanggal Mulai</th>
                <th class="px-4 py-3">Tanggal Selesai</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($periods as $period)
                <tr class="{{ $period->is_active ? 'bg-green-50' : '' }}">
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $period->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $period->type }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $period->start_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $period->end_date->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        @if($period->is_active)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-600">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        @unless($period->is_active)
                            <form method="POST" action="{{ route('periode.activate', $period) }}" class="inline"
                                  x-data @submit.prevent="if(confirm('Aktifkan periode {{ $period->name }}? Periode aktif lainnya akan dinonaktifkan otomatis.')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-green-600 hover:underline">Aktifkan</button>
                            </form>
                        @endunless
                        <a href="{{ route('periode.edit', $period) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('periode.destroy', $period) }}" class="inline"
                              x-data @submit.prevent="if(confirm('Hapus periode {{ $period->name }}?')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-400">Tidak ada data periode.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $periods->links() }}
</div>
@endsection
