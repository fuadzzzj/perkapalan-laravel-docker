@extends('layouts.app')

@section('title', 'Dashboard Guru - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Dashboard Guru</h1>
        <div class="stats-grid">
            <article><strong>{{ $memberCount }}</strong><span>Anggota aktif</span></article>
            <article><strong>{{ $laporanCount }}</strong><span>Laporan masuk</span></article>
            <article><strong>{{ $nilaiCount }}</strong><span>Nilai tercatat</span></article>
        </div>
        <a class="dash-link" href="{{ route('guru.academic') }}">Buka panel akademik</a>
    </section>
@endsection
