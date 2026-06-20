@extends('layouts.app')

@section('title', 'Mapping Penilai')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Mapping Penilai</h1>
    <div class="flex gap-2">
        @if($mappings->where('is_done', true)->count() > 0)
            <form method="POST" action="{{ route('mapping.reset-period', $selectedPeriodId) }}" class="inline"
                  x-data @submit.prevent="if(confirm('PERINGATAN: Ini akan menghapus SEMUA hasil penilaian di periode ini dan mengembalikan status ke belum dinilai. Tindakan ini tidak bisa dibatalkan. Lanjutkan?')) $el.submit()">
                @csrf
                <button type="submit" class="bg-red-50 text-red-700 border border-red-200 text-sm px-4 py-2 rounded hover:bg-red-100">
                    Reset Semua Periode Ini
                </button>
            </form>
        @endif
        <a href="{{ route('mapping.create', ['period_id' => $selectedPeriodId]) }}" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
            + Tambah Mapping
        </a>
    </div>
</div>

<p class="text-sm text-slate-500 mb-4">
    Mapping menentukan <strong>siapa boleh menilai siapa</strong>. Penilai hanya bisa mengisi penilaian untuk karyawan yang sudah terdaftar di mapping ini.
</p>

{{-- Filter --}}
<form method="GET" class="flex gap-2 mb-4">
    <select name="period_id" onchange="this.form.submit()" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        @foreach($periods as $period)
            <option value="{{ $period->id }}" @selected($selectedPeriodId == $period->id)>
                {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
            </option>
        @endforeach
    </select>

    <select name="division_id" onchange="this.form.submit()" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        <option value="">Semua Bagian</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>{{ $division->name }}</option>
        @endforeach
    </select>
</form>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Karyawan Dinilai</th>
                <th class="px-4 py-3">Bagian</th>
                <th class="px-4 py-3">Penilai</th>
                <th class="px-4 py-3">Tipe Penilai</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
           @forelse($mappings as $mapping)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $mapping->employee->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mapping->employee->division->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $mapping->assessor->name }}</td>
                    <td class="px-4 py-3">
                        @if($mapping->assessor_type == 'atasan')
                            <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">Atasan Langsung</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-slate-100 text-slate-600">Rekan Kerja</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $mapping->status_badge }}">{{ $mapping->status_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if(!$mapping->is_done)
                            <form method="POST" action="{{ route('mapping.destroy', $mapping) }}" class="inline"
                                  x-data @submit.prevent="if(confirm('Hapus mapping {{ $mapping->assessor->name }} -> {{ $mapping->employee->name }}?')) $el.submit()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('mapping.reset', $mapping) }}" class="inline"
                                  x-data @submit.prevent="if(confirm('Reset penilaian {{ $mapping->assessor->name }} untuk {{ $mapping->employee->name }}? Hasil penilaian yang sudah ada akan dihapus dan penilai bisa mengisi ulang.')) $el.submit()">
                                @csrf
                                <button type="submit" class="text-amber-600 hover:underline">Reset</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-400">
                        Belum ada mapping untuk periode ini. Klik "+ Tambah Mapping" untuk memulai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $mappings->links() }}
</div>
@endsection
