@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Karyawan: {{ $employee->name }}</h1>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 max-w-2xl">
    <form method="POST" action="{{ route('karyawan.update', $employee) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Bagian</label>
                <select name="division_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" @selected(old('division_id', $employee->division_id) == $division->id)>{{ $division->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Level Karyawan</label>
                <select name="level" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <option value="1" @selected(old('level', $employee->level) == 1)>Level 1 - Operator/Staff</option>
                    <option value="2" @selected(old('level', $employee->level) == 2)>Level 2 - Ka. Bagian</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Status Kontrak</label>
            <select name="contract_status" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                @foreach(['Tetap', 'Kontrak', 'Probation', 'Magang'] as $status)
                    <option value="{{ $status }}" @selected(old('contract_status', $employee->contract_status) == $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <hr class="border-slate-200">
        <p class="text-sm font-semibold text-slate-600">Akun Login</p>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $employee->user->email ?? '') }}" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Role Akun</label>
            <select name="role" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                <option value="karyawan" @selected(old('role', $employee->user->role ?? '') == 'karyawan')>Karyawan (lihat hasil sendiri)</option>
                <option value="penilai" @selected(old('role', $employee->user->role ?? '') == 'penilai')>Penilai (bisa menilai sesuai mapping)</option>
                <option value="hr" @selected(old('role', $employee->user->role ?? '') == 'hr')>HR (akses penuh)</option>
            </select>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $employee->is_active)) class="mr-2">
            <label for="is_active" class="text-sm text-slate-600">Karyawan aktif</label>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">Update</button>
            <a href="{{ route('karyawan.index') }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">Batal</a>
        </div>
    </form>
</div>
@endsection
