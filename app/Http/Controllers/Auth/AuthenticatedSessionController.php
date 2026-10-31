<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
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

        $request->session()->regenerate();

        return match ($user->role) {
            'admin', 'guru' => redirect()->route('admin.dashboard'),
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
