@extends('layouts.app')

@section('title', 'Edit Periode')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Edit Periode: {{ $period->name }}</h1>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 max-w-lg">
    <form method="POST" action="{{ route('periode.update', $period) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Periode</label>
            <input type="text" name="name" value="{{ old('name', $period->name) }}" required
                   class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Periode</label>
            <select name="type" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(old('type', $period->type) == $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $period->start_date->format('Y-m-d')) }}" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $period->end_date->format('Y-m-d')) }}" required
                       class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">Update</button>
            <a href="{{ route('periode.index') }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">Batal</a>
        </div>
    </form>
</div>
@endsection
