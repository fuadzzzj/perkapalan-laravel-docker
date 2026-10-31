<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('member.dashboard', [
            'user' => $user,
            'laporanCount' => Laporan::query()->where('user_id', $user->id)->count(),
            'nilaiCount' => $user->nilaiPraktikum()->count(),
        ]);
    }
}
