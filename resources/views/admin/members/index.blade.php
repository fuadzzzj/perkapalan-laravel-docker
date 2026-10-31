@extends('layouts.app')

@section('title', 'Persetujuan Anggota - Perkapalan')

@section('content')
    <section class="dashboard">
        <h1>Persetujuan Anggota</h1>

        @if ($errors->any())
            <div class="error-message" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <p class="success-message" role="status">{{ session('status') }}</p>
        @endif

        <h2>Menunggu Persetujuan</h2>
        @forelse ($pendingMembers as $member)
            <article class="member-row">
                <div>
                    <strong>{{ $member->nama_lengkap }}</strong>
                    <span>{{ $member->username }} · {{ $member->email }} · {{ $member->nis }} · {{ $member->kelas }}</span>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ route('admin.members.approve', $member) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('admin.members.reject', $member) }}">
                        @csrf
                        @method('PATCH')
                        <button class="danger" type="submit">Tolak</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="empty-note">Tidak ada pendaftaran yang menunggu.</p>
        @endforelse

        <h2>Daftar Anggota</h2>
        @foreach ($members as $member)
            <article class="member-row">
                <div><strong>{{ $member->nama_lengkap }}</strong><span>{{ $member->username }} · {{ $member->membership_status }}{{ $member->isActivated() ? ' · Aktif' : ' · Belum aktif' }}</span></div>
                @if ($member->isApproved() && ! $member->isActivated())
                    <form method="POST" action="{{ route('admin.members.activation.resend', $member) }}">
                        @csrf
                        <button type="submit">Kirim ulang tautan aktivasi</button>
                    </form>
                @endif
            </article>
        @endforeach
    </section>
@endsection
