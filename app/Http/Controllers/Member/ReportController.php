<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikum;
use App\Models\Laporan;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('member.reports.index', [
            'reports' => Laporan::query()->where('user_id', $user->id)->latest('tanggal_kirim')->get(),
            'scores' => $user->nilaiPraktikum()->latest()->get(),
            'schedules' => JadwalPraktikum::query()->orderBy('tanggal')->orderBy('jam')->get(),
            'announcements' => Pengumuman::query()->latest()->limit(10)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'file_laporan' => ['required', 'file', 'mimes:pdf,zip,rar', 'max:10240'],
        ]);

        $path = Storage::disk('public')->putFile('laporan', $validated['file_laporan']);

        Laporan::create([
            'user_id' => $request->user()->id,
            'judul' => $validated['judul'],
            'status' => 'menunggu',
            'tanggal_kirim' => now()->toDateString(),
            'file_path' => $path,
        ]);

        return back()->with('status', 'Laporan berhasil dikirim dan menunggu peninjauan guru.');
    }
}
