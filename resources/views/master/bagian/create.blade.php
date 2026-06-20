@extends('layouts.app')

@section('title', 'Tambah Bagian')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Tambah Bagian</h1>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 max-w-lg">
    <form method="POST" action="{{ route('bagian.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Bagian</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">Simpan</button>
            <a href="{{ route('bagian.index') }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">Batal</a>
        </div>
    </form>
</div>
@endsection
