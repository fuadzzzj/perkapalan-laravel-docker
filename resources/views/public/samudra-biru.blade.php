@extends('layouts.app')
@section('title', 'Samudra Biru - Perkapalan')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/samudra_biru.css') }}">
@endsection
@section('content')
<section class="hero" id="beranda">
    <div class="hero-content">
        <div class="eyebrow">Kegiatan Kelautan &amp; Maritim</div>
        <h1>Mengasah <span>Semangat Laut</span> melalui Praktik Nyata</h1>
        <p>Samudra Biru menyiapkan siswa memahami dunia maritim, keselamatan perairan, navigasi, dan keterampilan teknis yang dibutuhkan industri kelautan modern.</p>
        <div class="hero-actions"><a class="btn btn-primary" href="#kegiatan">Lihat Kegiatan</a><a class="btn btn-secondary" href="#materi">Materi yang Diajar</a></div>
    </div>
</section>

<div class="page-body">
<section class="section" id="tentang"><div class="container about"><div class="about-visual"><img src="{{ asset('images/samudra_biru/samudra biru.png') }}" alt="Kegiatan Samudra Biru"><div class="badge">Belajar dengan pendekatan praktik dan lingkungan nyata</div></div><div class="about-card"><div class="section-header about-header"><div class="label">Tentang Samudra Biru</div><h2>Menumbuhkan semangat maritim sejak dini</h2></div><p>Samudra Biru menyatukan teori, simulasi, dan praktik lapangan untuk membentuk karakter siswa yang disiplin, berani, dan siap menghadapi tantangan laut.</p><div class="mini-grid"><div class="mini-item"><strong>Praktik</strong><span>Belajar langsung di lapangan</span></div><div class="mini-item"><strong>Simulasi</strong><span>Situasi maritim nyata</span></div><div class="mini-item"><strong>Keselamatan</strong><span>Prioritas setiap kegiatan</span></div><div class="mini-item"><strong>Karakter</strong><span>Disiplin dan tanggung jawab</span></div></div></div></div></section>

<section class="section programs" id="kegiatan"><div class="container"><div class="section-header"><div class="label">Kegiatan Kami</div><h2>Aktivitas yang Kami Lakukan</h2><div class="divider"></div></div><div class="cards">
@foreach ([['Navigasi','Pengenalan Arah & Rute Laut','Siswa membaca peta, memahami kompas, dan mengenal arah perjalanan laut.','1db1ad8b7a963ef74e9b4335d6093503.jpg'],['Simulasi','Latihan Situasi Darurat','Peserta memahami evakuasi dan koordinasi tim dalam kondisi kritis.','8a2bf94d7dcde50cd6e8ccec886a6b79.jpg'],['Keselamatan','Pelatihan Keselamatan di Perairan','Penggunaan alat keselamatan dan prosedur penyelamatan diri.','3.png'],['Komunikasi','Komunikasi Radio & Pelayaran','Komunikasi jelas dan efektif menggunakan prosedur radio.','samudra biru.png']] as $card)
<article class="card"><div class="card-image"><img src="{{ asset('images/samudra_biru/'.$card[3]) }}" alt="{{ $card[1] }}"></div><div class="card-body"><div class="tag">{{ $card[0] }}</div><h3>{{ $card[1] }}</h3><p>{{ $card[2] }}</p></div></article>@endforeach
</div></div></section>

<section class="section" id="materi"><div class="container"><div class="section-header"><div class="label">Materi Pembelajaran</div><h2>Apa yang kami ajarkan?</h2><div class="divider"></div></div><div class="materi-wrap"><div class="materi-list"><div class="materi-item"><div class="materi-ico"><i class="fas fa-compass"></i></div><div><h4>Navigasi Dasar Laut</h4><p>Kompas, peta, arah angin, posisi kapal, dan kondisi perairan.</p></div></div><div class="materi-item"><div class="materi-ico">📡</div><div><h4>Komunikasi Radio &amp; Signal</h4><p>Prosedur komunikasi antar kapal yang aman dan efektif.</p></div></div><div class="materi-item"><div class="materi-ico">🦺</div><div><h4>Keselamatan &amp; Survival</h4><p>Alat keselamatan, penyelamatan diri, dan keadaan darurat.</p></div></div></div><div class="materi-visual"><img src="{{ asset('images/samudra_biru/syaykh.png') }}" alt="Materi pembelajaran Samudra Biru"><ul class="bullet-list"><li>Kesiapan mental dan fisik di laut</li><li>Sistem keamanan dan standar operasional</li><li>Tanggung jawab dan kerja sama tim</li></ul></div></div></div></section>

<section class="section gallery" id="galeri"><div class="container"><div class="section-header"><div class="label">Dokumentasi</div><h2>Galeri Kegiatan</h2><div class="divider"></div></div><div class="gallery-grid"><div class="gallery-item"><img src="{{ asset('images/samudra_biru/1db1ad8b7a963ef74e9b4335d6093503.jpg') }}" alt="Navigasi laut"><div class="gallery-caption">Latihan navigasi</div></div><div class="gallery-item"><img src="{{ asset('images/samudra_biru/3.png') }}" alt="Simulasi laut"><div class="gallery-caption">Simulasi keselamatan</div></div><div class="gallery-item"><img src="{{ asset('images/samudra_biru/samudra biru.png') }}" alt="Pembelajaran maritim"><div class="gallery-caption">Pembelajaran maritim</div></div></div></div></section>
</div>
@endsection
@section('scripts')<script src="{{ asset('js/samudra_biru.js') }}" defer></script>@endsection
