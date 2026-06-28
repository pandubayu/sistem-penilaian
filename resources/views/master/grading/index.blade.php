@extends('layouts.app')

@section('title', 'Setting Grading Threshold')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Setting Grading Threshold</h1>
<p class="text-sm text-slate-500 mb-6">
    Atur batas nilai (min-max) untuk setiap grade, beserta reward/punishment-nya. Perubahan akan langsung berlaku untuk penghitungan raport selanjutnya.
</p>

@foreach($thresholds as $level => $rows)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-slate-800 mb-3">
            Level {{ $level }} — {{ $level == 2 ? 'Ka. Bagian' : 'Operator/Staff' }}
        </h2>

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="table-responsive">
    <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-left">
                    <tr>
                        <th class="px-4 py-3 w-20">Grade</th>
                        <th class="px-4 py-3 w-32">Min Nilai</th>
                        <th class="px-4 py-3 w-32">Max Nilai</th>
                        <th class="px-4 py-3">Reward</th>
                        <th class="px-4 py-3">Punishment</th>
                        <th class="px-4 py-3 w-24 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rows as $threshold)
                        <tr>
                            <form method="POST" action="{{ route('grading.update', $threshold) }}">
                                @csrf
                                @method('PUT')

                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $threshold->grade_badge }} font-bold">{{ $threshold->grade }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="min_score" value="{{ $threshold->min_score }}" required
                                           class="w-24 border border-slate-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="max_score" value="{{ $threshold->max_score }}" placeholder="∞"
                                           class="w-24 border border-slate-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="reward_text" value="{{ $threshold->reward_text }}"
                                           class="w-full border border-slate-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="punishment_text" value="{{ $threshold->punishment_text }}"
                                           class="w-full border border-slate-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="submit" class="text-blue-600 hover:underline text-sm">Simpan</button>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
        </div>
    </div>
@endforeach

<div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded p-4">
    <strong>Catatan:</strong> kolom "Max Nilai" dikosongkan berarti tidak ada batas atas (khusus Grade A). Pastikan rentang nilai antar grade tidak bertabrakan atau bocor (ada celah).
</div>
@endsection
