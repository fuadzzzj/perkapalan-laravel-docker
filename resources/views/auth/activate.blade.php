@extends('layouts.app')

@section('title', 'Aktifkan Akun - Perkapalan')

@section('content')
    <section class="auth-card">
        <h1>Aktifkan Akun</h1>
        <p>Buat kata sandi untuk menyelesaikan aktivasi akun Anda.</p>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('activation.store', ['user' => $userId, 'token' => $token]) }}">
            @csrf
            <label for="password">Kata sandi baru</label>
            <input id="password" name="password" type="password" minlength="12" autocomplete="new-password" required>

            <label for="password_confirmation">Ulangi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="12" autocomplete="new-password" required>

            <button type="submit">Aktifkan akun</button>
        </form>
    </section>
@endsection
