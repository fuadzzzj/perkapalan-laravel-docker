<?php

namespace App\Services;

use App\Mail\MemberActivationMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MemberActivationService
{
    public function send(User $member): void
    {
        abort_unless($member->isAnggota() && $member->isApproved() && $member->email, 422);

        $token = Str::random(64);

        DB::table('member_activation_tokens')->updateOrInsert(
            ['user_id' => $member->id],
            [
                'token_hash' => hash('sha256', $token),
                'expires_at' => now()->addHours(24),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        Mail::to($member->email)->send(new MemberActivationMail(
            route('activation.create', ['user' => $member->id, 'token' => $token]),
        ));
    }
}
