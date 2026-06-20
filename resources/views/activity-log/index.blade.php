@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<h1 class="text-2xl font-bold text-slate-800 mb-6">Activity Log</h1>

{{-- Filter --}}
<form method="GET" class="flex gap-2 mb-4 flex-wrap">
    <select name="user_id" class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">
        <option value="">Semua User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
        @endforeach
    </select>

    <input type="text" name="action" value="{{ request('action') }}" placeholder="Cari aksi (contoh: mapping)..."
           class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">

    <input type="date" name="date_from" value="{{ request('date_from') }}"
           class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">

    <input type="date" name="date_to" value="{{ request('date_to') }}"
           class="border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500">

    <button type="submit" class="bg-slate-200 text-slate-700 text-sm px-4 py-2 rounded hover:bg-slate-300">Filter</button>

    @if(request()->anyFilled(['user_id', 'action', 'date_from', 'date_to']))
        <a href="{{ route('activity-log.index') }}" class="text-sm text-slate-500 px-3 py-2 hover:underline">Reset</a>
    @endif
</form>

<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-left">
            <tr>
                <th class="px-4 py-3">Waktu</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Aksi</th>
                <th class="px-4 py-3">Model</th>
                <th class="px-4 py-3">IP Address</th>
                <th class="px-4 py-3">Detail</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($logs as $log)
                <tr>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $log->user->name ?? 'Sistem' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs bg-slate-100 text-slate-700">{{ $log->action_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        {{ $log->model_type ? class_basename($log->model_type) : '-' }}
                        @if($log->model_id)
                            <span class="text-slate-400">#{{ $log->model_id }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $log->ip_address ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($log->old_data || $log->new_data)
                            <details>
                                <summary class="text-blue-600 cursor-pointer text-xs">Lihat detail</summary>
                                <div class="mt-2 text-xs space-y-2 max-w-md">
                                    @if($log->old_data)
                                        <div>
                                            <p class="font-medium text-slate-500">Sebelum:</p>
                                            <pre class="bg-slate-50 rounded p-2 overflow-x-auto">{{ json_encode($log->old_data, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                    @if($log->new_data)
                                        <div>
                                            <p class="font-medium text-slate-500">Sesudah:</p>
                                            <pre class="bg-slate-50 rounded p-2 overflow-x-auto">{{ json_encode($log->new_data, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada activity log.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection
