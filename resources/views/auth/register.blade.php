@extends('layouts.app')

@section('title', 'Daftar Anggota - Perkapalan')

@section('content')
    <section class="auth-card">
        <h1>Pendaftaran Anggota</h1>
        <p>Admin akan memeriksa pendaftaran Anda. Jika disetujui, tautan untuk membuat kata sandi akan dikirim ke email.</p>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label for="nama_lengkap">Nama Lengkap</label>
            <input id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>

            <label for="username">Username</label>
            <input id="username" name="username" value="{{ old('username') }}" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>

            <label for="nis">NIS</label>
            <input id="nis" name="nis" value="{{ old('nis') }}" required>

            <label for="kelas">Kelas</label>
            <input id="kelas" name="kelas" value="{{ old('kelas') }}" required>

            <label for="jurusan">Jurusan</label>
            <input id="jurusan" name="jurusan" value="{{ old('jurusan', 'Teknik Perkapalan') }}" required>

            <label for="tahun_masuk">Tahun Masuk</label>
            <input id="tahun_masuk" name="tahun_masuk" type="number" min="2000" max="{{ now()->year + 1 }}" value="{{ old('tahun_masuk', now()->year) }}" required>

            <button type="submit">Daftar</button>
        </form>
    </section>
@endsection
