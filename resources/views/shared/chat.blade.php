@extends('layouts.app')

@section('title', 'Ruang Chat - Perkapalan')

@section('content')
    <section class="dashboard chat-room">
        <h1>Ruang Chat Bersama</h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="chat-messages">
            @forelse ($messages as $message)
                <article class="chat-bubble">
                    <header>
                        <strong>{{ $message->user?->nama_lengkap ?? $message->user?->username }}</strong>
                        <span>{{ $message->created_at?->format('d M H:i') }}</span>
                    </header>
                    @if ($message->pesan)
                        <p>{{ $message->pesan }}</p>
                    @endif
                    @if ($message->media_path)
                        @if (($message->media_type ?? null) === 'image')
                            <a href="{{ route('chat.media', $message) }}" target="_blank" rel="noopener">
                                <img class="chat-media" src="{{ route('chat.media', $message) }}" alt="Lampiran gambar">
                            </a>
                        @else
                            <a class="chat-attachment" href="{{ route('chat.media', $message) }}" target="_blank" rel="noopener">Buka lampiran</a>
                        @endif
                    @endif
                </article>
            @empty
                <p class="empty-note">Belum ada pesan. Mulai percakapan pertama!</p>
            @endforelse
        </div>

        <form class="chat-form" method="POST" action="{{ route('chat.store') }}" enctype="multipart/form-data">
            @csrf
            <input name="pesan" maxlength="1000" placeholder="Tulis pesan" value="{{ old('pesan') }}" aria-label="Pesan">
            <input name="media" type="file" aria-label="Lampiran">
            <button type="submit">Kirim</button>
        </form>
    </section>
@endsection
