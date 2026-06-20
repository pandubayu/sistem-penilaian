@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Data Karyawan</h1>
    <a href="{{ route('karyawan.create') }}" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">
        + Tambah Karyawan
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="flex gap-2 mb-4">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIK..."
           class="border border-slate-300 rounded px-3 py-2 text-sm flex-1 max-w-xs focus:outline-none focus:ring-2 focus:ring-slate-500">

    <select name="division_id" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        <option value="">Semua Bagian</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}" @selected(request('division_id') == $division->id)>{{ $division->name }}</option>
        @endforeach
    </select>

    <button type="submit" class="bg-slate-200 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-300">Filter</button>

    @if(request('search') || request('division_id'))
        <a href="{{ route('karyawan.index') }}" class="text-sm text-slate-500 px-3 py-2 hover:underline">Reset</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">NIK</th>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Bagian</th>
                <th class="px-4 py-3">Level</th>
                <th class="px-4 py-3">Status Kontrak</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Aktif</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($employees as $employee)
                <tr>
                    <td class="px-4 py-3 font-mono text-slate-700">{{ $employee->nik }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $employee->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $employee->division->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $employee->level_label }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $employee->contract_badge }}">{{ $employee->contract_status }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $employee->user->email ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($employee->is_active)
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-600">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('karyawan.edit', $employee) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('karyawan.destroy', $employee) }}" class="inline"
                              x-data @submit.prevent="if(confirm('Hapus karyawan {{ $employee->name }}?')) $el.submit()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-slate-400">Tidak ada data karyawan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $employees->links() }}
</div>
@endsection
