@extends('layouts.app')

@section('title', 'Profil SMK Perkapalan Al-Zaytun')

@section('content')
<section class="section section-profil">
    <div class="container grid-2">
        <div class="profil-content fade-in">
            <span class="section-label">Tentang Kami</span>
            <h1 class="section-title">Profil Perkapalan Al-Zaytun</h1>
            <p class="section-text">Kami membentuk generasi maritim yang profesional, berakhlak, dan siap bersaing di era global melalui pendidikan berbasis kompetensi.</p>
            <p class="section-text">Pembelajaran menggabungkan teori, workshop, praktik pengelasan, keselamatan, navigasi, dan permesinan kapal.</p>
        </div>
        <div class="profil-image-wrapper fade-in delay-200">
            <img src="{{ asset('images/prestasi/ustad imam.jpeg') }}" alt="Profil SMK Perkapalan Al-Zaytun">
            <div class="image-badge">Akreditasi A</div>
        </div>
    </div>
</section>
<section class="section section-fasilitas" id="program"><div class="container"><div class="section-header text-center"><span class="section-label">Program</span><h2 class="section-title">Keunggulan Pembelajaran</h2></div><div class="grid-3"><a class="card card-fasilitas" href="{{ route('public.workshop') }}"><div class="card-image"><img src="{{ asset('images/workshop/workshop.jpg') }}" alt="Workshop"></div><div class="card-content"><h3 class="card-title">Workshop Perkapalan</h3><p class="card-desc">Pertukangan, desain, permesinan, dan uji kapabilitas kapal.</p></div></a><a class="card card-fasilitas" href="{{ route('public.teknik-besi') }}"><div class="card-image"><img src="{{ asset('images/teknik_besi/las.jpg') }}" alt="Teknik Besi"></div><div class="card-content"><h3 class="card-title">Teknik Besi &amp; Pengelasan</h3><p class="card-desc">Fabrikasi, K3, las manual, dan MIG/MAG.</p></div></a><a class="card card-fasilitas" href="{{ route('public.samudra-biru') }}"><div class="card-image"><img src="{{ asset('images/samudra_biru/3.png') }}" alt="Samudra Biru"></div><div class="card-content"><h3 class="card-title">Samudra Biru</h3><p class="card-desc">Navigasi, komunikasi radio, dan keselamatan laut.</p></div></a></div></div></section>
@endsection