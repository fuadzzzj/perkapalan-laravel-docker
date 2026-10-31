@extends('layouts.app')
@section('title', 'SMK Perkapalan Al-Zaytun')
@section('content')
<header class="hero">
    <img class="hero-image" src="{{ asset('images/samudra_biru/samudra biru.png') }}" alt="Foto Kapal Perkapalan" loading="eager">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h2 class="hero-title">Membangun Generasi Maritim Unggul</h2>
        <p class="hero-subtitle"><strong>Perkapalan Al-Zaytun</strong> berkomitmen mencetak lulusan kompeten di bidang kelautan dan perkapalan dengan standar industri nasional.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Masuk Sebagai Anggota</a>
    </div>
    <a class="hero-scroll" href="#profil-section" aria-label="Lihat profil">⌄</a>
</header>

<section id="profil-section" class="section section-profil">
    <div class="container grid-2">
        <div class="profil-content fade-in">
            <span class="section-label">Tentang Kami</span>
            <h2 class="section-title">Visi &amp; Misi SMK Perkapalan Al-Zaytun</h2>
            <p class="section-text">Kami berkomitmen untuk menghasilkan tenaga ahli perkapalan yang profesional, berakhlak mulia, dan siap bersaing di era global.</p>
            <p class="section-text">Dengan kurikulum berbasis kompetensi dan fasilitas praktikum modern, siswa dibekali keterampilan teknis dan soft skills yang relevan dengan kebutuhan industri maritim.</p>
            <div class="profil-stats">
                <div class="stat-item"><span class="stat-number">500+</span><span class="stat-label">Lulusan</span></div>
                <div class="stat-item"><span class="stat-number">15+</span><span class="stat-label">Instruktur</span></div>
                <div class="stat-item"><span class="stat-number">98%</span><span class="stat-label">Penyerapan Kerja</span></div>
            </div>
        </div>
        <div class="profil-image-wrapper fade-in delay-200">
            <img src="{{ asset('images/prestasi/ustad imam.jpeg') }}" alt="Fasilitas SMK Perkapalan">
            <div class="image-badge">Akreditasi A</div>
        </div>
    </div>
</section>

<section id="fasilitas-section" class="section section-fasilitas">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-label">Fasilitas</span>
            <h2 class="section-title">Laboratorium &amp; Praktik</h2>
            <p class="section-desc">Dilengkapi dengan sarana modern untuk mendukung pembelajaran berbasis praktik.</p>
        </div>
        <div class="grid-3">
            <a class="card card-fasilitas fade-in" href="{{ route('public.workshop') }}"><div class="card-image"><img src="{{ asset('images/workshop/workshop.jpg') }}" alt="Workshop Perkapalan"></div><div class="card-content"><h3 class="card-title">Workshop Perkapalan</h3><p class="card-desc">Area praktik kapal, mesin, dan fabrikasi dengan pendekatan industri modern.</p><span class="card-action">Lihat halaman →</span></div></a>
            <a class="card card-fasilitas fade-in delay-100" href="{{ route('public.teknik-besi') }}"><div class="card-image"><img src="{{ asset('images/teknik_besi/las.jpg') }}" alt="Teknik Besi dan Pengelasan"></div><div class="card-content"><h3 class="card-title">Teknik Besi &amp; Pengelasan</h3><p class="card-desc">Pelatihan pengelasan, pemotongan, dan fabrikasi logam.</p><span class="card-action">Lihat halaman →</span></div></a>
            <a class="card card-fasilitas fade-in delay-200" href="{{ route('public.samudra-biru') }}"><div class="card-image"><img src="{{ asset('images/samudra_biru/3.png') }}" alt="Program Samudra Biru"></div><div class="card-content"><h3 class="card-title">Samudra Biru</h3><p class="card-desc">Pembelajaran maritim dan navigasi berbasis simulasi untuk kesiapan operasional.</p><span class="card-action">Lihat halaman →</span></div></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container grid-3">
        <a class="card card-fasilitas" href="{{ route('public.tenaga-ahli') }}"><div class="card-image"><img src="{{ asset('images/tenaga ahli/fuad.jpg') }}" alt="Guru dan Tenaga Ahli"></div><div class="card-content"><h3 class="card-title">Guru &amp; Tenaga Ahli</h3><p class="card-desc">Pengajar berpengalaman dan tenaga ahli perkapalan.</p></div></a>
        <a class="card card-fasilitas" href="{{ route('public.prestasi') }}"><div class="card-image"><img src="{{ asset('images/prestasi/prestasi 1.jpg') }}" alt="Prestasi siswa"></div><div class="card-content"><h3 class="card-title">Prestasi</h3><p class="card-desc">Capaian akademik dan kompetisi siswa Perkapalan Al-Zaytun.</p></div></a>
        <a class="card card-fasilitas" href="{{ route('register') }}"><div class="card-content"><span class="section-label">Gabung</span><h3 class="card-title">Daftar Anggota</h3><p class="card-desc">Ajukan akun anggota dan tunggu persetujuan admin.</p><span class="card-action">Daftar sekarang →</span></div></a>
    </div>
</section>
@endsection
