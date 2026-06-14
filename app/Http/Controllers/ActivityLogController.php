<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat seluruh aktivitas user di sistem.
     * Bisa difilter per user dan per jenis aksi.
     */
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->user_id, fn ($q, $userId) => $q->where('user_id', $userId))
            ->when($request->action, fn ($q, $action) => $q->where('action', 'like', "%{$action}%"))
            ->when($request->date_from, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('activity-log.index', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
