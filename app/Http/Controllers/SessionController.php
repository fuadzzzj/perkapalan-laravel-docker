<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
        }

        /** @var User $user */
        $user = $request->user();

        if (! $user->isApproved()) {
            Auth::logout();
            $message = $user->membership_status === 'rejected'
                ? 'Pendaftaran Anda ditolak oleh admin.'
                : 'Akun Anda masih menunggu persetujuan admin.';

            return back()->withErrors(['username' => $message])->onlyInput('username');
        }

        if (! $user->isActivated()) {
            Auth::logout();

            return back()->withErrors(['username' => 'Akun Anda belum diaktifkan. Gunakan tautan yang dikirim melalui email.'])->onlyInput('username');
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            default => redirect()->route('member.dashboard'),
        };
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
