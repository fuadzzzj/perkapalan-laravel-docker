<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MemberActivationController extends Controller
{
    public function create(int $user, string $token): View|RedirectResponse
    {
        if (! $this->tokenIsValid($user, $token)) {
            return redirect()->route('login')->withErrors(['activation' => 'Tautan aktivasi tidak berlaku atau sudah kedaluwarsa.']);
        }

        return view('auth.activate', ['userId' => $user, 'token' => $token]);
    }

    public function store(Request $request, int $user, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'string', 'min:12'],
        ]);

        $activated = DB::transaction(function () use ($user, $token, $validated): bool {
            $activation = DB::table('member_activation_tokens')
                ->where('user_id', $user)
                ->where('token_hash', hash('sha256', $token))
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            $member = User::query()->whereKey($user)
                ->where('role', 'anggota')
                ->where('membership_status', 'approved')
                ->lockForUpdate()
                ->first();

            if (! $activation || ! $member || $member->activated_at) {
                return false;
            }

            $member->forceFill([
                'password' => Hash::make($validated['password']),
                'activated_at' => now(),
                'email_verified_at' => now(),
            ])->save();

            DB::table('member_activation_tokens')->where('id', $activation->id)->delete();

            return true;
        });

        if (! $activated) {
            return redirect()->route('login')->withErrors(['activation' => 'Tautan aktivasi tidak berlaku atau sudah digunakan.']);
        }

        return redirect()->route('login')->with('status', 'Akun aktif. Silakan masuk dengan username dan kata sandi baru.');
    }

    private function tokenIsValid(int $userId, string $token): bool
    {
        return User::query()->whereKey($userId)
            ->where('role', 'anggota')
            ->where('membership_status', 'approved')
            ->whereNull('activated_at')
            ->exists()
            && DB::table('member_activation_tokens')
                ->where('user_id', $userId)
                ->where('token_hash', hash('sha256', $token))
                ->where('expires_at', '>', now())
                ->exists();
    }
}
