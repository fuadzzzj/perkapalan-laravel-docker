@extends('layouts.app')

@section('title', 'Dashboard Anggota - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Dashboard Anggota</h1>
        <p>Selamat datang, {{ $user->nama_lengkap ?? $user->name }}.</p>
        <div class="stats-grid">
            <article><strong>{{ $laporanCount }}</strong><span>Laporan dikirim</span></article>
            <article><strong>{{ $nilaiCount }}</strong><span>Nilai tercatat</span></article>
        </div>
        <a class="dash-link" href="{{ route('member.portal') }}">Buka portal anggota</a>
    </section>
@endsection
