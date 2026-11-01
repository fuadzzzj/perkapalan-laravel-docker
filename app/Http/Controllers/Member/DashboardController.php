<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikum;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $averageScore = $user->nilaiPraktikum()->avg('nilai');
        $scheduleQuery = JadwalPraktikum::query()
            ->whereDate('tanggal', '>=', today())
            ->where(function ($query) use ($user): void {
                $query->whereNull('kelas');
                if ($user->kelas) {
                    $query->orWhere('kelas', $user->kelas);
                }
            });

        return view('member.dashboard', [
            'user' => $user,
            'laporanCount' => Laporan::query()->where('user_id', $user->id)->count(),
            'nilaiCount' => $user->nilaiPraktikum()->count(),
            'averageScore' => $averageScore !== null ? round((float) $averageScore, 1) : null,
            'schedules' => $scheduleQuery->orderBy('tanggal')->orderBy('jam')->limit(4)->get(),
        ]);
    }
}
