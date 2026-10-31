<?php

namespace App\Http\Controllers;

use App\Models\JadwalPraktikum;
use App\Models\Laporan;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberReportController extends Controller
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

        $path = Storage::disk('local')->putFile('laporan', $validated['file_laporan']);

        Laporan::create([
            'user_id' => $request->user()->id,
            'judul' => $validated['judul'],
            'status' => 'menunggu',
            'tanggal_kirim' => now()->toDateString(),
            'file_path' => $path,
        ]);

        return back()->with('status', 'Laporan berhasil dikirim dan menunggu peninjauan guru.');
    }

    public function download(Laporan $laporan): StreamedResponse
    {
        $this->authorize('view', $laporan);

        $disk = Storage::disk('local')->exists($laporan->file_path) ? 'local' : 'public';
        abort_unless(Storage::disk($disk)->exists($laporan->file_path), 404);

        $extension = pathinfo($laporan->file_path, PATHINFO_EXTENSION);

        return Storage::disk($disk)->download($laporan->file_path, 'laporan-'.$laporan->id.($extension ? '.'.$extension : ''));
    }
}
