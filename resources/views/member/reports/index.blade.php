@extends('layouts.app')

@section('title', 'Portal Anggota - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Portal Anggota</h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h2>Upload Laporan</h2>
        <form class="stack-form" method="POST" action="{{ route('member.laporan.store') }}" enctype="multipart/form-data">
            @csrf
            <label>Judul<input name="judul" value="{{ old('judul') }}" required></label>
            <label>File PDF/ZIP/RAR<input type="file" name="file_laporan" accept=".pdf,.zip,.rar" required></label>
            <button type="submit">Upload</button>
        </form>

        <h2>Laporan</h2>
        @forelse ($reports as $report)
            <article class="member-row">
                <div>
                    <strong>{{ $report->judul }}</strong>
                    <span class="badge-status status-{{ $report->status }}">{{ $report->status }}</span>
                </div>
                <a href="{{ route('member.laporan.download', $report) }}">Unduh laporan</a>
            </article>
        @empty
            <p class="empty-note">Belum ada laporan.</p>
        @endforelse

        <h2>Nilai Praktikum</h2>
        @forelse ($scores as $score)
            <article class="member-row">
                <strong>{{ $score->mata_pelajaran }}</strong>
                <span>{{ $score->nilai }} · {{ $score->semester }}</span>
            </article>
        @empty
            <p class="empty-note">Belum ada nilai.</p>
        @endforelse

        <h2>Jadwal</h2>
        @forelse ($schedules as $schedule)
            <article class="member-row">
                <strong>{{ $schedule->mata_pelajaran }}</strong>
                <span>{{ $schedule->tanggal->format('d M Y') }} · {{ $schedule->jam }} · {{ $schedule->ruang }}</span>
            </article>
        @empty
            <p class="empty-note">Belum ada jadwal.</p>
        @endforelse

        <h2>Pengumuman</h2>
        @forelse ($announcements as $announcement)
            <article class="note-card">
                <h3>{{ $announcement->judul }}</h3>
                <p>{{ $announcement->isi }}</p>
            </article>
        @empty
            <p class="empty-note">Belum ada pengumuman.</p>
        @endforelse
    </section>
@endsection
