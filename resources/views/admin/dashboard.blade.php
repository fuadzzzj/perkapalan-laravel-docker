@extends('layouts.app')

@section('title', 'Dashboard Admin - Perkapalan')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboards.css') }}">
@endsection

@section('content')
    <section class="dashboard school-dashboard" aria-label="Dashboard Admin">
        @include('partials.dashboard-sidebar', [
            'roleLabel' => 'Admin',
            'menuItems' => [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => '⌂'],
                ['label' => 'Persetujuan Anggota', 'route' => 'admin.members.index', 'icon' => '♙'],
                ['label' => 'Ruang Chat', 'route' => 'chat.index', 'icon' => '◌'],
            ],
        ])

        <div class="school-dashboard__main">
            <header class="school-dashboard__hero">
                <img src="{{ asset('images/workshop/anggota.jpg') }}" alt="Siswa mengikuti kegiatan praktik di workshop perkapalan">
                <div class="school-dashboard__hero-content">
                    <span class="school-dashboard__eyebrow">PORTAL ADMIN · {{ now()->format('d/m/Y') }}</span>
                    <h1>Dashboard Admin</h1>
                    <p>Selamat datang, {{ auth()->user()->nama_lengkap ?: auth()->user()->name }}. Pantau anggota dan persetujuan pendaftaran sekolah.</p>
                </div>
                <span class="school-dashboard__hero-mark" aria-hidden="true">⚓</span>
            </header>

            <section class="school-dashboard__metrics" aria-label="Ringkasan sekolah">
                <article class="school-dashboard__metric school-dashboard__metric--blue"><span class="school-dashboard__metric-icon" aria-hidden="true">♙</span><div><strong>{{ $pendingCount }}</strong><span>Pendaftaran menunggu</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--teal"><span class="school-dashboard__metric-icon" aria-hidden="true">⚓</span><div><strong>{{ $memberCount }}</strong><span>Anggota aktif</span></div></article>
                <article class="school-dashboard__metric school-dashboard__metric--gold"><span class="school-dashboard__metric-icon" aria-hidden="true">✧</span><div><strong>{{ $guruCount }}</strong><span>Guru</span></div></article>
            </section>

            <div class="school-dashboard__lower-grid">
                <section class="school-dashboard__panel school-dashboard__approval-panel">
                    <div class="school-dashboard__panel-heading"><span class="school-dashboard__panel-icon" aria-hidden="true">♙</span><div><span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">PENERIMAAN ANGGOTA</span><h2>Persetujuan pendaftaran</h2></div></div>
                    @if ($pendingCount > 0)
                        <p>Ada <strong>{{ $pendingCount }} pendaftaran</strong> yang perlu ditinjau. Periksa data pendaftar dan kirim tautan aktivasi setelah disetujui.</p>
                    @else
                        <p>Belum ada pendaftaran yang menunggu. Daftar anggota dan status aktivasi dapat dikelola dari halaman persetujuan.</p>
                    @endif
                    <a class="school-dashboard__button" href="{{ route('admin.members.index') }}">Kelola persetujuan <span aria-hidden="true">→</span></a>
                </section>

                <section class="school-dashboard__panel school-dashboard__quick-panel">
                    <span class="school-dashboard__eyebrow school-dashboard__eyebrow--dark">AKSES CEPAT</span>
                    <h2>Kelola kegiatan sekolah</h2>
                    <p>Gunakan ruang chat untuk berkoordinasi dengan guru dan anggota.</p>
                    <a class="school-dashboard__text-link" href="{{ route('chat.index') }}">Buka ruang chat <span aria-hidden="true">↗</span></a>
                </section>
            </div>
        </div>
    </section>
@endsection
