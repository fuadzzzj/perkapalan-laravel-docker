@extends('layouts.app')

@section('title', 'Panel Akademik Guru - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Panel Akademik</h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h2>Nilai Praktikum</h2>
        <form class="stack-form" method="POST" action="{{ route('guru.nilai.store') }}">
            @csrf
            <select name="user_id" required aria-label="Anggota">
                <option value="">Pilih anggota</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}">{{ $member->nama_lengkap }} · {{ $member->kelas }}</option>
                @endforeach
            </select>
            <input name="mata_pelajaran" placeholder="Mata pelajaran" required>
            <input name="nilai" type="number" min="0" max="100" step="0.01" placeholder="Nilai (0-100)" required>
            <input name="semester" placeholder="Semester" required>
            <button type="submit">Simpan Nilai</button>
        </form>

        <h2>Jadwal Praktikum</h2>
        <form class="stack-form" method="POST" action="{{ route('guru.jadwal.store') }}" enctype="multipart/form-data">
            @csrf
            <input name="mata_pelajaran" placeholder="Mata pelajaran" required>
            <input name="tanggal" type="date" required aria-label="Tanggal">
            <input name="jam" type="time" required aria-label="Jam">
            <input name="ruang" placeholder="Ruang" required>
            <select name="kelas" required aria-label="Kelas">
                <option value="">Pilih kelas tujuan</option>
                @foreach ($classes as $class)
                    <option value="{{ $class }}" @selected(old('kelas') === $class)>{{ $class }}</option>
                @endforeach
            </select>
            <input name="foto_jadwal" type="file" accept="image/jpeg,image/png,image/webp" aria-label="Foto jadwal">
            <button type="submit">Tambah Jadwal</button>
        </form>

        <h2>Jadwal yang sudah dibuat</h2>
        @forelse ($schedules as $schedule)
            <article class="member-row">
                <div>
                    <strong>{{ $schedule->mata_pelajaran }} · {{ $schedule->kelas ?: 'Umum' }}</strong>
                    <span>{{ $schedule->tanggal->format('d M Y') }} · {{ substr($schedule->jam, 0, 5) }} · {{ $schedule->ruang }}</span>
                </div>
            </article>
        @empty
            <p class="empty-note">Belum ada jadwal praktikum.</p>
        @endforelse

        <h2>Pengumuman</h2>
        <form class="stack-form" method="POST" action="{{ route('guru.pengumuman.store') }}">
            @csrf
            <input name="judul" placeholder="Judul" required>
            <textarea name="isi" rows="4" placeholder="Isi pengumuman" required></textarea>
            <button type="submit">Terbitkan</button>
        </form>

        <h2>Laporan Masuk</h2>
        @forelse ($reports as $report)
            <article class="member-row">
                <div>
                    <strong>{{ $report->judul }}</strong>
                    <span>{{ $report->user?->nama_lengkap }} · {{ $report->status }}</span>
                    <a href="{{ route('guru.laporan.download', $report) }}">Unduh laporan</a>
                </div>
                <form class="actions" method="POST" action="{{ route('guru.laporan.status', $report) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" aria-label="Status laporan">
                        @foreach (['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'revisi' => 'Revisi'] as $value => $label)
                            <option value="{{ $value }}" @selected($report->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Ubah</button>
                </form>
            </article>
        @empty
            <p class="empty-note">Belum ada laporan.</p>
        @endforelse
    </section>
@endsection
