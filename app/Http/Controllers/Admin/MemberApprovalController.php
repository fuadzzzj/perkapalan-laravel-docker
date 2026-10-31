<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MemberActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemberApprovalController extends Controller
{
    public function index(): View
    {
        return view('admin.members.index', [
            'pendingMembers' => User::query()->where('membership_status', 'pending')->orderBy('created_at')->get(),
            'members' => User::query()->where('role', 'anggota')->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function approve(Request $request, User $user, MemberActivationService $activation): RedirectResponse
    {
        if (! $user->isAnggota() || $user->membership_status !== 'pending') {
            return back()->withErrors(['member' => 'Pendaftaran ini tidak lagi menunggu persetujuan.']);
        }

        if (! $user->email) {
            return back()->withErrors(['member' => 'Anggota harus memiliki email sebelum dapat disetujui.']);
        }

        $user->update([
            'membership_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);
        $activation->send($user);

        return back()->with('status', 'Anggota disetujui. Tautan aktivasi dikirim ke email anggota.');
    }

    public function resendActivation(User $user, MemberActivationService $activation): RedirectResponse
    {
        if (! $user->isAnggota() || ! $user->isApproved() || $user->isActivated()) {
            return back()->withErrors(['member' => 'Akun ini tidak memerlukan tautan aktivasi.']);
        }

        $activation->send($user);

        return back()->with('status', 'Tautan aktivasi baru dikirim ke email anggota.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if (! $user->isAnggota() || $user->membership_status !== 'pending') {
            return back()->withErrors(['member' => 'Pendaftaran ini tidak lagi menunggu keputusan.']);
        }

        DB::table('member_activation_tokens')->where('user_id', $user->id)->delete();
        $user->update([
            'membership_status' => 'rejected',
            'approved_at' => null,
            'approved_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Pendaftaran anggota ditolak.');
    }
}
