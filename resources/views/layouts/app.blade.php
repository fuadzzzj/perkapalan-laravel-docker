<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SMK Perkapalan Al-Zaytun - Membangun Generasi Maritim Unggul">
    <meta name="theme-color" content="#a9e1f6">
    <title>@yield('title', 'SMK Perkapalan Al-Zaytun')</title>
    <link rel="icon" href="{{ asset('images/beranda/logo jae.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/service_navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/maritime.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/glass.css') }}">
</head>
<body>
    @include('partials.sea-background')
    <a href="#main-content" class="skip-link">Langsung ke konten</a>

    <header id="site-nav-header"@auth class="has-user"@endauth>
        <div class="site-nav-bar">
            <a class="site-nav-brand" href="{{ route('home') }}" aria-label="Beranda SMK Perkapalan Al-Zaytun">
                <span class="site-nav-brand-mark" aria-hidden="true"></span>
                <span>SMK Perkapalan Al-Zaytun</span>
            </a>

            <nav class="site-nav-links" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('home') ? 'site-nav-current' : '' }}" href="{{ route('home') }}" {{ request()->routeIs('home') ? 'aria-current=page' : '' }}>Beranda</a>
                <a class="{{ request()->routeIs('profil') ? 'site-nav-current' : '' }}" href="{{ route('profil') }}" {{ request()->routeIs('profil') ? 'aria-current=page' : '' }}>Profil</a>
                <a class="{{ request()->routeIs('public.teknik-besi') ? 'site-nav-current' : '' }}" href="{{ route('public.teknik-besi') }}" {{ request()->routeIs('public.teknik-besi') ? 'aria-current=page' : '' }}>Teknik Besi</a>
                <a class="{{ request()->routeIs('public.samudra-biru') ? 'site-nav-current' : '' }}" href="{{ route('public.samudra-biru') }}" {{ request()->routeIs('public.samudra-biru') ? 'aria-current=page' : '' }}>Samudra Biru</a>
                <a class="{{ request()->routeIs('public.workshop') ? 'site-nav-current' : '' }}" href="{{ route('public.workshop') }}" {{ request()->routeIs('public.workshop') ? 'aria-current=page' : '' }}>Workshop</a>
                <a class="{{ request()->routeIs('public.prestasi') ? 'site-nav-current' : '' }}" href="{{ route('public.prestasi') }}" {{ request()->routeIs('public.prestasi') ? 'aria-current=page' : '' }}>Prestasi</a>
                <a class="{{ request()->routeIs('public.tenaga-ahli') ? 'site-nav-current' : '' }}" href="{{ route('public.tenaga-ahli') }}" {{ request()->routeIs('public.tenaga-ahli') ? 'aria-current=page' : '' }}>Tenaga Ahli</a>
            </nav>

            <div class="site-nav-actions">
                @auth
                    <a class="site-nav-chat" href="{{ route('chat.index') }}">Chat</a>
                    @if (auth()->user()->isAdmin())
                        <a class="site-nav-cta" href="{{ route('admin.dashboard') }}">Admin</a>
                    @elseif (auth()->user()->isGuru())
                        <a class="site-nav-cta" href="{{ route('guru.dashboard') }}">Guru</a>
                    @else
                        <a class="site-nav-cta" href="{{ route('member.dashboard') }}">Anggota</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="site-nav-logout">
                        @csrf
                        <button type="submit">Keluar</button>
                    </form>
                @else
                    <a class="site-nav-cta" href="{{ route('login') }}">Masuk</a>
                @endauth
            </div>

            <button class="site-nav-burger" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="site-nav-sheet">
                <span></span><span></span>
            </button>
        </div>

        <div class="site-nav-sheet" id="site-nav-sheet" aria-hidden="true">
            <nav aria-label="Menu seluler">
                <a href="{{ route('home') }}" style="--site-nav-index: 0">Beranda</a>
                <a href="{{ route('profil') }}" style="--site-nav-index: 1">Profil</a>
                <a href="{{ route('public.teknik-besi') }}" style="--site-nav-index: 2">Teknik Besi</a>
                <a href="{{ route('public.samudra-biru') }}" style="--site-nav-index: 3">Samudra Biru</a>
                <a href="{{ route('public.workshop') }}" style="--site-nav-index: 4">Workshop</a>
                <a href="{{ route('public.prestasi') }}" style="--site-nav-index: 5">Prestasi</a>
                <a href="{{ route('public.tenaga-ahli') }}" style="--site-nav-index: 6">Tenaga Ahli</a>
            </nav>
            @auth
                @if (auth()->user()->isAdmin())
                    <a class="site-nav-cta" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                @elseif (auth()->user()->isGuru())
                    <a class="site-nav-cta" href="{{ route('guru.dashboard') }}">Dashboard Guru</a>
                @else
                    <a class="site-nav-cta" href="{{ route('member.dashboard') }}">Dashboard Anggota</a>
                @endif
                <a class="site-nav-cta" href="{{ route('chat.index') }}">Chat</a>
                <form method="POST" action="{{ route('logout') }}" class="site-nav-mobile-auth">
                    @csrf
                    <button class="site-nav-cta" type="submit">Keluar</button>
                </form>
            @else
                <a class="site-nav-cta" href="{{ route('login') }}">Masuk</a>
            @endauth
        </div>
    </header>

    <main id="main-content">
        @if (session('status')) <div class="container status-message">{{ session('status') }}</div> @endif
        @yield('content')
    </main>

    <footer class="foot" id="kontak">
        <div class="foot-in">
            <div class="foot-top">
                <div class="foot-brand">
                    <a class="foot-logo" href="{{ route('home') }}"><i aria-hidden="true"></i>Al-Zaytun Shipyard</a>
                    <p>Workshop pembuatan kapal Ponpes Al-Zaytun - memadukan ilmu, keterampilan, dan akhlaq untuk kemaslahatan umat.</p>
                </div>
                <nav aria-label="Jelajah">
                    <h3>Jelajah</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('home') }}#profil-section">Profil</a></li>
                        <li><a href="{{ route('home') }}#fasilitas-section">Program</a></li>
                        <li><a href="{{ route('public.prestasi') }}">Prestasi</a></li>
                    </ul>
                </nav>
                <div>
                    <h3>Program</h3>
                    <ul>
                        <li><a href="{{ route('public.workshop') }}">Workshop Perkapalan</a></li>
                        <li><a href="{{ route('public.teknik-besi') }}">Teknik Besi</a></li>
                        <li><a href="{{ route('public.samudra-biru') }}">Samudra Biru</a></li>
                        <li><a href="{{ route('public.tenaga-ahli') }}">Tenaga Ahli</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Hubungi</h3>
                    <ul>
                        <li>Ponpes Al-Zaytun, Indramayu</li>
                        <li><a href="tel:+622341234567">(0234) 123-4567</a></li>
                        <li><a href="mailto:workshop@alzaytun.id">workshop@alzaytun.id</a></li>
                    </ul>
                </div>
            </div>
            <div class="foot-big" aria-hidden="true">Perkapalan</div>
            <div class="foot-bottom">
                <small>&copy; {{ date('Y') }} Workshop Kapal Ponpes Al-Zaytun. Semua hak dilindungi.</small>
                <a class="foot-totop" href="#main-content">Kembali ke atas</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('error', function (event) {
            if (event.target && event.target.tagName === 'IMG') { event.target.classList.add('img-missing'); }
        }, true);
    </script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ asset('js/navbar.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
