<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penilaian Prestasi Karyawan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-800 text-white min-h-screen flex-shrink-0">
        <div class="p-4 border-b border-slate-700">
            <h1 class="text-lg font-bold">Sistem Penilaian</h1>
            <p class="text-xs text-slate-400 mt-1">{{ auth()->user()->name }}</p>
            <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded bg-slate-700 uppercase">{{ auth()->user()->role }}</span>
        </div>

        <nav class="p-2 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700' : '' }}">
                Dashboard
            </a>

            @if(auth()->user()->isHr())
                <p class="px-3 pt-3 pb-1 text-xs uppercase text-slate-500">Data Master</p>
                <a href="{{ route('karyawan.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('karyawan.*') ? 'bg-slate-700' : '' }}">Karyawan</a>
                <a href="{{ route('bagian.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('bagian.*') ? 'bg-slate-700' : '' }}">Bagian</a>
                <a href="{{ route('periode.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('periode.*') ? 'bg-slate-700' : '' }}">Periode</a>
                <a href="{{ route('kriteria-teknis.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('kriteria-teknis.*') ? 'bg-slate-700' : '' }}">Kriteria Teknis</a>
                <a href="{{ route('kriteria-umum.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('kriteria-umum.*') ? 'bg-slate-700' : '' }}">Kriteria Umum</a>
                <a href="{{ route('grading.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('grading.*') ? 'bg-slate-700' : '' }}">Grading</a>

                <p class="px-3 pt-3 pb-1 text-xs uppercase text-slate-500">Penilaian</p>
                <a href="{{ route('mapping.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('mapping.*') ? 'bg-slate-700' : '' }}">Mapping Penilai</a>
                <a href="{{ route('laporan.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('laporan.*') ? 'bg-slate-700' : '' }}">Laporan Raport</a>
                <a href="{{ route('activity-log.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('activity-log.*') ? 'bg-slate-700' : '' }}">Activity Log</a>
            @endif

            @if(auth()->user()->isPenilai())
                <p class="px-3 pt-3 pb-1 text-xs uppercase text-slate-500">Tugas Saya</p>
                <a href="{{ route('penilaian.create') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('penilaian.*') ? 'bg-slate-700' : '' }}">Isi Penilaian</a>
            @endif

            @if(auth()->user()->isKaryawan())
                <p class="px-3 pt-3 pb-1 text-xs uppercase text-slate-500">Saya</p>
                <a href="{{ route('hasil.saya') }}" class="block px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('hasil.saya') ? 'bg-slate-700' : '' }}">Hasil Penilaian Saya</a>
            @endif
        </nav>

        <div class="p-2 mt-auto border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-slate-700 text-sm text-red-300">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 p-6">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
