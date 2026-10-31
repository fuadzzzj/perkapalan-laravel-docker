@extends('layouts.app')
@section('title', 'Prestasi Perkapalan - SMK Perkapalan Al-Zaytun')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/prestasi.css') }}">
    <style>
        .prestasi-page .hero {
            background: linear-gradient(135deg, rgba(10,37,64,.88), rgba(30,95,140,.78)), url("{{ asset('images/prestasi/ustad imam.jpeg') }}") center 25%/cover no-repeat;
        }
    </style>
@endsection
@section('content')
<div class="prestasi-page">
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <p class="eyebrow">Prestasi Kami</p>
            <h1>Mengukir <span>Keberhasilan</span> di Dunia Maritim</h1>
            <p>Dari lomba teknis hingga kegiatan maritim, setiap prestasi adalah hasil kerja keras, semangat, dan perjalanan panjang para siswa perkapalan kami.</p>
            <a class="btn btn-primary" href="#prestasi">Lihat Prestasi</a>
        </div>
    </section>

    <div id="prestasi" class="section">
        <div class="container">
            <div class="section-header">
                <p class="label">Prestasi Terbaru</p>
                <h2>Perjalanan kami dalam meraih juara</h2>
            </div>

            <div class="prestasi-layout">
                <div class="achievement-list" aria-label="Daftar prestasi">
                    <button class="achievement-card active" type="button" data-id="lomba-kapal">
                        <span class="badge">Juara 1</span>
                        <h3>Lomba Kapal Mini Antar Sekolah</h3>
                        <p>Desain dan pembuatan kapal mini dengan konsep efisien dan inovatif.</p>
                    </button>
                    <button class="achievement-card" type="button" data-id="lomba-navigasi">
                        <span class="badge">Juara 2</span>
                        <h3>Lomba Navigasi &amp; Keselamatan Laut</h3>
                        <p>Kemampuan membaca peta, koordinasi tim, dan simulasi situasi darurat.</p>
                    </button>
                    <button class="achievement-card" type="button" data-id="lomba-las">
                        <span class="badge">Juara 3</span>
                        <h3>Kompetisi Las &amp; Fabrikasi</h3>
                        <p>Ketelitian dalam pengelasan, kualitas sambungan, dan hasil finishing.</p>
                    </button>
                    <button class="achievement-card" type="button" data-id="lomba-inovasi">
                        <span class="badge">Finalis</span>
                        <h3>Inovasi Teknologi Perkapalan</h3>
                        <p>Ide kreatif untuk solusi kerja di sektor maritim dan teknologi kapal.</p>
                    </button>
                </div>

                <article class="achievement-detail" id="detail-prestasi">
                    <div class="detail-header"><p class="label">Detail Prestasi</p><h2 id="detail-title">Lomba Kapal Mini Antar Sekolah</h2></div>
                    <div class="detail-cover"><img id="detail-image" src="{{ asset('images/workshop/workshop.jpg') }}" alt="Lomba kapal mini"></div>
                    <div class="detail-meta"><span id="detail-rank">Juara 1</span><span id="detail-year">2025</span></div>
                    <div class="detail-content">
                        <p id="detail-summary">Tim perkapalan kami berhasil menjuarai lomba kapal mini antar sekolah dengan desain yang ringan, stabil, dan efisien.</p>
                        <h3>Cerita perjalanan</h3>
                        <p id="detail-story">Awalnya, kami mencoba membuat prototype kapal mini dari bahan sederhana. Setiap kesalahan membuat kami belajar lebih teliti tentang lambung, keseimbangan, dan kerja sama tim.</p>
                        <div class="gallery-grid" id="detail-gallery">
                            <img src="{{ asset('images/prestasi/prestasi 1.jpg') }}" alt="Proses pembuatan kapal">
                            <img src="{{ asset('images/prestasi/prestasi 2.jpg') }}" alt="Perjalanan lomba">
                            <img src="{{ asset('images/workshop/workshop.jpg') }}" alt="Tim perkapalan">
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('js/prestasi.js') }}" defer></script>
@endsection
