@extends('layouts.app')

@section('title', 'Masuk - Perkapalan')

@section('content')
    <section class="auth-card">
        <h1>Masuk</h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="username">Username</label>
            <input id="username" name="username" value="{{ old('username') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <label class="checkbox-label">
                <input name="remember" type="checkbox" value="1">
                Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>

        <p>Belum punya akun? <a href="{{ route('register') }}">Daftar anggota</a></p>
    </section>
@endsection
