<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', ['classes' => config('school.classes')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'string', Rule::in(config('school.classes'))],
            'nis' => ['required', 'string', 'max:255', 'unique:users,nis'],
            'jurusan' => ['required', 'string', 'max:255'],
            'tahun_masuk' => ['required', 'integer', 'min:2000', 'max:' . (now()->year + 1)],
        ]);

        User::create([
            ...$validated,
            'name' => $validated['nama_lengkap'],
            'password' => Str::random(64),
            'role' => 'anggota',
            'membership_status' => 'pending',
        ]);

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil. Setelah disetujui admin, tautan aktivasi akan dikirim ke email Anda.');
    }
}
