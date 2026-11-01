@extends('layouts.app')

@section('title', 'Dashboard Guru - Perkapalan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards.css') }}">
@endsection

@section('content')
    <section class="dashboard school-dashboard" aria-label="Dashboard Guru">
        @include('partials.dashboard-sidebar', [
            'roleLabel' => 'Guru',
            'menuItems' => [
                ['label' => 'Dashboard', 'route' => 'guru.dashboard', 'icon' => '⌂'],
                ['label' => 'Panel Akademik', 'route' => 'guru.academic', 'icon' => '▤'],
                ['label' => 'Ruang Chat', 'route' => 'chat.index', 'icon' => '◌'],
            ],
        ])

        <div class="school-dashboard__main">
            <header class="school-dashboard__hero">
                <img src="{{ asset('images/workshop/anggota.jpg') }}" alt="Siswa mengikuti kegiatan praktik di workshop perkapalan">
                <div class="school-dashboard__hero-content">
                    <span class="school-dashboard__eyebrow">PORTAL GURU · {{ now()->format('d/m/Y') }}</span>
                    <h1>Dashboard Guru</h1>
                    <p>Selamat datang, {{ auth()->user()->nama_lengkap ?: auth()->user()->name }}. Kelola pembelajaran dan pantau perkembangan anggota.</p>
                </div>
                <span class="school-dashboard__hero-mark" aria-hidden="true">⚓</span>
            </header>

            <section class="school-dashboard__metrics" aria-label="Ringkasan akademik">
                <article class="school-dashboard__metric school-dashboard__metric--blue"><span class="school-dashboard__metric-icon" aria-hidden="true">♙</span><div><strong>{{ $memberCount }}</strong><span>Anggota aktif</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--teal"><span class="school-dashboard__metric-icon" aria-hidden="true">▤</span><div><strong>{{ $laporanCount }}</strong><span>Laporan masuk</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--gold"><span class="school-dashboard__metric-icon" aria-hidden="true">★</span><div><strong>{{ $nilaiCount }}</strong><span>Nilai tercatat</span></div></article>
            </section>

            <div class="school-dashboard__lower-grid">
                <section class="school-dashboard__panel school-dashboard__academic-panel">
                    <div class="school-dashboard__panel-heading"><span class="school-dashboard__panel-icon" aria-hidden="true">✎</span><div><span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">PENGELOLAAN PEMBELAJARAN</span><h2>Panel akademik</h2></div></div>
                    <p>Atur jadwal praktikum, terbitkan pengumuman, input nilai, dan tinjau laporan anggota dari satu halaman.</p>
                    <a class="school-dashboard__button" href="{{ route('guru.academic') }}">Buka panel akademik <span aria-hidden="true">→</span></a>
                </section>

                <section class="school-dashboard__panel school-dashboard__quick-panel">
                    <span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">KOORDINASI</span>
                    <h2>Ruang chat sekolah</h2>
                    <p>Berkomunikasi dengan admin, guru, dan anggota yang sudah masuk.</p>
                    <a class="school-dashboard__text-link" href="{{ route('chat.index') }}">Buka ruang chat <span aria-hidden="true">↗</span></a>
                </section>
            </div>
        </div>
    </section>
@endsection
