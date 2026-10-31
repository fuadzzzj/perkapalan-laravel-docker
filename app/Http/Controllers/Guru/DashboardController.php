<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\NilaiPraktikum;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('guru.dashboard', [
            'memberCount' => User::query()->where('role', 'anggota')->where('membership_status', 'approved')->count(),
            'laporanCount' => Laporan::query()->count(),
            'nilaiCount' => NilaiPraktikum::query()->count(),
        ]);
    }
}
