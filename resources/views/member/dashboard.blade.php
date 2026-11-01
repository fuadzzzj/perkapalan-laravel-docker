@extends('layouts.app')

@section('title', 'Dashboard Anggota - Perkapalan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards.css') }}">
@endsection

@section('content')
    <section class="dashboard school-dashboard" aria-label="Dashboard Anggota">
        @include('partials.dashboard-sidebar', [
            'roleLabel' => 'Anggota',
            'menuItems' => [
                ['label' => 'Dashboard', 'route' => 'member.dashboard', 'icon' => '⌂'],
                ['label' => 'Portal Anggota', 'route' => 'member.portal', 'icon' => '▤'],
                ['label' => 'Ruang Chat', 'route' => 'chat.index', 'icon' => '◌'],
            ],
        ])

        <div class="school-dashboard__main">
            <header class="school-dashboard__hero">
                <img src="{{ asset('images/workshop/anggota.jpg') }}" alt="Siswa mengikuti kegiatan praktik di workshop perkapalan">
                <div class="school-dashboard__hero-content">
                    <span class="school-dashboard__eyebrow">PORTAL ANGGOTA · {{ now()->format('d/m/Y') }}</span>
                    <h1>Dashboard Anggota</h1>
                    <p>Selamat datang, {{ $user->nama_lengkap ?: $user->name }}. Pantau laporan dan nilai praktikum kamu di sini.</p>
                </div>
                <span class="school-dashboard__hero-mark" aria-hidden="true">⚓</span>
            </header>

            <section class="school-dashboard__metrics" aria-label="Ringkasan belajar">
                <article class="school-dashboard__metric school-dashboard__metric--blue"><span class="school-dashboard__metric-icon" aria-hidden="true">▤</span><div><strong>{{ $laporanCount }}</strong><span>Laporan dikirim</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--teal"><span class="school-dashboard__metric-icon" aria-hidden="true">▣</span><div><strong>{{ $nilaiCount }}</strong><span>Nilai tercatat</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--gold"><span class="school-dashboard__metric-icon" aria-hidden="true">★</span><div><strong>{{ $averageScore !== null ? number_format($averageScore, 1, ',', '.') : '—' }}</strong><span>Rata-rata nilai</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--blue"><span class="school-dashboard__metric-icon school-dashboard__metric-icon--class" aria-hidden="true">K</span><div><strong class="school-dashboard__class-value">{{ $user->kelas ?: '—' }}</strong><span>Kelas kamu</span></div></article>
            </section>

            <section class="school-dashboard__panel school-dashboard__schedule-panel" aria-labelledby="class-schedule-heading">
                <div class="school-dashboard__panel-heading">
                    <span class="school-dashboard__panel-icon" aria-hidden="true">▦</span>
                    <div><span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">JADWAL PRAKTIKUM</span><h2 id="class-schedule-heading">Jadwal kelas {{ $user->kelas ?: 'belum dipilih' }}</h2></div>
                </div>
                @forelse ($schedules as $schedule)
                    <article class="school-dashboard__schedule-row">
                        <span class="school-dashboard__schedule-date"><strong>{{ $schedule->tanggal->format('d') }}</strong><small>{{ $schedule->tanggal->format('M') }}</small></span>
                        <span class="school-dashboard__schedule-info"><strong>{{ $schedule->mata_pelajaran }}</strong><small>{{ $schedule->kelas ?: 'Umum' }} · {{ substr($schedule->jam, 0, 5) }} · {{ $schedule->ruang }}</small></span>
                    </article>
                @empty
                    <p class="school-dashboard__empty">Belum ada jadwal praktikum berikutnya untuk kelas ini.</p>
                @endforelse
            </section>

            <div class="school-dashboard__lower-grid">
                <section class="school-dashboard__panel school-dashboard__portal-panel">
                    <div class="school-dashboard__panel-heading"><span class="school-dashboard__panel-icon" aria-hidden="true">⚓</span><div><span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">KEGIATAN BELAJAR</span><h2>Portal anggota</h2></div></div>
                    <p>Unggah laporan praktikum, lihat nilai, cek jadwal, dan baca pengumuman dari guru.</p>
                    <a class="school-dashboard__button" href="{{ route('member.portal') }}">Buka portal anggota <span aria-hidden="true">→</span></a>
                </section>

                <section class="school-dashboard__panel school-dashboard__quick-panel">
                    <span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">BUTUH BANTUAN?</span>
                    <h2>Hubungi guru</h2>
                    <p>Gunakan ruang chat sekolah untuk bertanya atau berkoordinasi dengan guru.</p>
                    <a class="school-dashboard__text-link" href="{{ route('chat.index') }}">Buka ruang chat <span aria-hidden="true">↗</span></a>
                </section>
            </div>
        </div>
    </section>
@endsection
