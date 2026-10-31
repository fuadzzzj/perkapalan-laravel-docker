@extends('layouts.app')

@section('title', 'Dashboard Admin - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Dashboard Admin</h1>
        <div class="stats-grid">
            <article><strong>{{ $pendingCount }}</strong><span>Pendaftaran menunggu</span></article>
            <article><strong>{{ $memberCount }}</strong><span>Anggota aktif</span></article>
            <article><strong>{{ $guruCount }}</strong><span>Guru</span></article>
        </div>
        <a class="dash-link" href="{{ route('admin.members.index') }}">Kelola persetujuan anggota</a>
    </section>
@endsection
