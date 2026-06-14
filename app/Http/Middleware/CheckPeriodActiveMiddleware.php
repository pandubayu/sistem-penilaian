<?php

namespace App\Http\Middleware;

use App\Models\Period;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPeriodActiveMiddleware
{
    /**
     * Cek apakah ada periode penilaian yang sedang aktif.
     * Dipakai untuk membatasi akses ke halaman penilaian (1, 2, 3)
     * supaya penilai tidak bisa mengisi form kalau tidak ada periode aktif.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activePeriod = Period::active()->first();

        if (!$activePeriod) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Tidak ada periode penilaian yang sedang aktif. Hubungi HR.');
        }

        // Simpan periode aktif ke request supaya bisa dipakai di controller
        // tanpa query ulang
        $request->attributes->set('active_period', $activePeriod);

        return $next($request);
    }
}
