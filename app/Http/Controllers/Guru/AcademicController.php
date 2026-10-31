<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktikum;
use App\Models\Laporan;
use App\Models\NilaiPraktikum;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function index(): View
    {
        return view('guru.academic', [
            'members' => User::query()->where('role', 'anggota')->where('membership_status', 'approved')->orderBy('nama_lengkap')->get(),
            'reports' => Laporan::query()->with('user')->latest('tanggal_kirim')->get(),
            'schedules' => JadwalPraktikum::query()->latest('tanggal')->limit(20)->get(),
            'announcements' => Pengumuman::query()->latest()->limit(20)->get(),
        ]);
    }

    public function storeScore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'mata_pelajaran' => ['required', 'string', 'max:255'],
            'nilai' => ['required', 'numeric', 'between:0,100'],
            'semester' => ['required', 'string', 'max:255'],
        ]);

        NilaiPraktikum::create($validated);

        return back()->with('status', 'Nilai praktikum berhasil disimpan.');
    }

    public function storeSchedule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mata_pelajaran' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i'],
            'ruang' => ['required', 'string', 'max:255'],
            'foto_jadwal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_jadwal')) {
            $fotoPath = Storage::disk('public')->putFile('jadwal', $validated['foto_jadwal']);
        }

        JadwalPraktikum::create([
            ...$validated,
            'foto_path' => $fotoPath,
            'dibuat_oleh' => $request->user()->id,
        ]);

        return back()->with('status', 'Jadwal praktikum berhasil ditambahkan.');
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
        ]);

        Pengumuman::create([
            ...$validated,
            'dibuat_oleh' => $request->user()->id,
        ]);

        return back()->with('status', 'Pengumuman berhasil diterbitkan.');
    }

    public function updateReport(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,disetujui,revisi'],
        ]);

        $laporan->update($validated);

        return back()->with('status', 'Status laporan berhasil diperbarui.');
    }
}
