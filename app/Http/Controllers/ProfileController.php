<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Tampilkan form ganti password
     */
    public function showChangePassword()
    {
        return view('profil.ganti-password');
    }

    /**
     * Proses ganti password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min'      => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama yang kamu masukkan salah.',
            ])->withInput();
        }

        // Cek apakah password baru sama dengan password lama
        if (Hash::check($request->password_baru, $user->password)) {
            return back()->withErrors([
                'password_baru' => 'Password baru tidak boleh sama dengan password lama.',
            ])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        // Catat ke activity log
        ActivityLog::record('change_password', $user, null, ['info' => 'Password berhasil diubah oleh user sendiri']);

        return redirect()->route('profil.ganti-password')
            ->with('success', 'Password berhasil diubah. Silakan gunakan password baru untuk login berikutnya.');
    }
}
