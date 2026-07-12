@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="max-w-lg">
    <h1 class="text-2xl font-bold text-slate-800 mb-1">Ganti Password</h1>
    <p class="text-sm text-slate-500 mb-6">
        Pastikan password baru kamu minimal 6 karakter dan mudah kamu ingat.
    </p>

    {{-- Info akun --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 mb-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-indigo-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
            <span class="inline-block text-[10px] px-1.5 py-0.5 rounded bg-green-100 text-green-700 uppercase font-semibold mt-0.5">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('profil.update-password') }}" class="space-y-4">
            @csrf

            {{-- Password Lama --}}
            <div>
                <label for="password_lama" class="block text-sm font-medium text-slate-700 mb-1">
                    Password Lama <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="password_lama"
                       id="password_lama"
                       required
                       autocomplete="current-password"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500
                              {{ $errors->has('password_lama') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                @error('password_lama')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-100">

            {{-- Password Baru --}}
            <div>
                <label for="password_baru" class="block text-sm font-medium text-slate-700 mb-1">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="password_baru"
                       id="password_baru"
                       required
                       autocomplete="new-password"
                       class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500
                              {{ $errors->has('password_baru') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                <p class="text-xs text-slate-400 mt-1">Minimal 6 karakter.</p>
                @error('password_baru')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <label for="password_baru_confirmation" class="block text-sm font-medium text-slate-700 mb-1">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="password_baru_confirmation"
                       id="password_baru_confirmation"
                       required
                       autocomplete="new-password"
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                <p class="text-xs text-slate-400 mt-1">Ketik ulang password baru untuk konfirmasi.</p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2 pt-2">
                <button type="submit"
                        class="bg-slate-800 text-white text-sm px-5 py-2 rounded hover:bg-slate-700 transition">
                    Simpan Password Baru
                </button>
                <a href="{{ route('dashboard') }}"
                   class="bg-slate-100 text-slate-700 text-sm px-5 py-2 rounded hover:bg-slate-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Info tambahan --}}
    <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded p-3">
        <strong>Catatan:</strong> Setelah ganti password, kamu tidak perlu login ulang. Password baru langsung berlaku untuk sesi berikutnya.
        Jika lupa password, hubungi HR untuk direset.
    </div>
</div>
@endsection
