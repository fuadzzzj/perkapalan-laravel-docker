@php
    $dashboardUser = auth()->user();
    $dashboardName = $dashboardUser->nama_lengkap ?: $dashboardUser->name;
    $dashboardInitials = collect(preg_split('/\s+/', trim($dashboardName)))
        ->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp

<aside class="school-dashboard__sidebar" aria-label="Menu portal {{ $roleLabel }}">
    <a class="school-dashboard__brand" href="{{ route('home') }}">
        <img src="{{ asset('images/beranda/logo jae.png') }}" alt="">
        <span><strong>Perkapalan</strong><small>Al-Zaytun</small></span>
    </a>
    <p class="school-dashboard__menu-label">MENU {{ mb_strtoupper($roleLabel) }}</p>
    <nav class="school-dashboard__nav">
        @foreach ($menuItems as $item)
            <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'is-active' : '' }}" @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                <span class="school-dashboard__nav-icon" aria-hidden="true">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
                @if (request()->routeIs($item['route'])) <span class="school-dashboard__nav-current" aria-hidden="true">›</span> @endif
            </a>
        @endforeach
    </nav>
    <div class="school-dashboard__sidebar-user">
        <span class="school-dashboard__avatar" aria-hidden="true">{{ $dashboardInitials ?: 'AZ' }}</span>
        <span><strong>{{ $dashboardName }}</strong><small>{{ $roleLabel }} Perkapalan</small></span>
    </div>
</aside>
