<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penilaian Prestasi Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-lg shadow-md w-full max-w-sm p-8">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-slate-800">Sistem Penilaian</h1>
            <p class="text-sm text-slate-500 mt-1">Prestasi Kerja Karyawan</p>
        </div>

        @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
            </div>

            <button type="submit"
                    class="w-full bg-slate-800 text-white rounded py-2 text-sm font-medium hover:bg-slate-700 transition">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>
