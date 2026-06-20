@extends('layouts.app')

@section('title', 'Tambah Mapping')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-1">Tambah Mapping Penilai</h1>
<p class="text-sm text-slate-500 mb-6">
    Pilih 1 karyawan yang akan dinilai, lalu tentukan siapa saja yang berhak menilainya (1 atasan + beberapa rekan).
</p>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- FORM --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6"
             x-data="{
                assessors: [{ assessor_id: '', assessor_type: 'atasan' }],
                addAssessor() {
                    this.assessors.push({ assessor_id: '', assessor_type: 'rekan' });
                },
                removeAssessor(index) {
                    if (this.assessors.length > 1) {
                        this.assessors.splice(index, 1);
                    }
                }
             }">
            <form method="POST" action="{{ route('mapping.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Periode</label>
                    <select name="period_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" @selected(old('period_id', $selectedPeriodId) == $period->id)>
                                {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Karyawan yang Dinilai</label>
                    <select name="employee_id" required class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>
                                {{ $employee->name }} ({{ $employee->division->name }} - {{ $employee->level_label }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="border-slate-200">

                <div class="flex justify-between items-center">
                    <label class="block text-sm font-medium text-slate-700">Daftar Penilai</label>
                    <button type="button" @click="addAssessor()" class="text-sm text-blue-600 hover:underline">
                        + Tambah Penilai
                    </button>
                </div>

                <template x-for="(item, index) in assessors" :key="index">
                    <div class="flex gap-2 items-start bg-slate-50 p-3 rounded">
                        <div class="flex-1">
                            <select :name="`assessors[${index}][assessor_id]`" x-model="item.assessor_id" required
                                    class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                <option value="">-- Pilih Penilai --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->division->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40">
                            <select :name="`assessors[${index}][assessor_type]`" x-model="item.assessor_type" required
                                    class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
                                <option value="atasan">Atasan Langsung</option>
                                <option value="rekan">Rekan Kerja</option>
                            </select>
                        </div>
                        <button type="button" @click="removeAssessor(index)"
                                class="text-red-600 hover:bg-red-50 rounded px-2 py-2 text-sm"
                                x-show="assessors.length > 1">
                            Hapus
                        </button>
                    </div>
                </template>

                <p class="text-xs text-slate-400">
                    Catatan: maksimal 1 "Atasan Langsung" per karyawan. Jumlah "Rekan Kerja" fleksibel (disarankan 2-3 orang).
                </p>

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="bg-slate-800 text-white text-sm px-4 py-2 rounded hover:bg-slate-700">Simpan Mapping</button>
                    <a href="{{ route('mapping.index', ['period_id' => $selectedPeriodId]) }}" class="bg-slate-100 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-200">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- PREVIEW MAPPING YANG SUDAH ADA --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <h2 class="text-sm font-semibold text-slate-700 mb-3">Mapping Sudah Ada (Periode Ini)</h2>

            @if($existingMappings->isEmpty())
                <p class="text-sm text-slate-400">Belum ada mapping di periode ini.</p>
            @else
                <div class="space-y-3 max-h-[500px] overflow-y-auto">
                    @foreach($existingMappings as $employeeId => $maps)
                        <div class="border border-slate-100 rounded p-2">
                            <p class="text-sm font-medium text-slate-800">{{ $maps->first()->employee->name }}</p>
                            <ul class="text-xs text-slate-500 mt-1 space-y-0.5">
                                @foreach($maps as $map)
                                    <li>
                                        - {{ $map->assessor->name }}
                                        <span class="text-slate-400">({{ $map->assessor_type_label }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
