@php
    $showcase = match (true) {
        request()->routeIs('home') => [
            'eyebrow' => 'Perkapalan Al-Zaytun',
            'title' => 'Membangun Generasi Maritim Unggul',
            'description' => 'Pendidikan perkapalan yang menggabungkan ilmu, keterampilan, dan pengalaman praktik untuk masa depan maritim.',
            'image' => 'images/samudra_biru/samudra biru.png',
            'alt' => 'Kegiatan dan kapal Perkapalan Al-Zaytun',
            'action' => 'Masuk sebagai anggota',
            'action_url' => route('login'),
        ],
        request()->routeIs('profil') => [
            'eyebrow' => 'Tentang Kami',
            'title' => 'Profil Perkapalan Al-Zaytun',
            'description' => 'Membentuk generasi maritim yang profesional, berakhlak, dan siap bersaing melalui pendidikan berbasis kompetensi.',
            'image' => 'images/beranda/461866554_518710570891506_6373918462459013762_n.png',
            'alt' => 'Profil Perkapalan Al-Zaytun',
            'action' => 'Lihat program',
            'action_url' => route('public.workshop'),
        ],
        request()->routeIs('public.prestasi') => [
            'eyebrow' => 'Pencapaian',
            'title' => 'Mengukir Keberhasilan di Dunia Maritim',
            'description' => 'Lihat karya, pengalaman, dan pencapaian peserta didik dalam bidang perkapalan.',
            'image' => 'images/prestasi/prestasi 1.jpg',
            'alt' => 'Dokumentasi prestasi Perkapalan Al-Zaytun',
            'action' => 'Lihat prestasi',
            'action_url' => '#prestasi',
        ],
        request()->routeIs('public.samudra-biru') => [
            'eyebrow' => 'Samudra Biru',
            'title' => 'Mengasah Semangat Laut melalui Praktik Nyata',
            'description' => 'Belajar navigasi, keselamatan, dan keterampilan maritim melalui pengalaman langsung.',
            'image' => 'images/samudra_biru/samudra biru.png',
            'alt' => 'Program Samudra Biru',
            'action' => 'Lihat kegiatan',
            'action_url' => '#kegiatan',
        ],
        request()->routeIs('public.teknik-besi') => [
            'eyebrow' => 'Teknik Besi',
            'title' => 'Seni Menyatukan Logam, Membentuk Karya',
            'description' => 'Kenali pembelajaran pengelasan, fabrikasi, dan keselamatan kerja dalam praktik vokasi.',
            'image' => 'images/teknik_besi/las.jpg',
            'alt' => 'Praktik pengelasan Teknik Besi',
            'action' => 'Pelajari materi',
            'action_url' => '#materi',
        ],
        request()->routeIs('public.tenaga-ahli') => [
            'eyebrow' => 'Guru dan Tenaga Ahli',
            'title' => 'Belajar Bersama Pembimbing Berpengalaman',
            'description' => 'Tim guru dan tenaga ahli membimbing peserta menjadi terampil, disiplin, dan siap berkarya.',
            'image' => 'images/tenaga ahli/fuad.jpg',
            'alt' => 'Guru dan tenaga ahli perkapalan',
            'action' => 'Lihat tim kami',
            'action_url' => '#tenaga-ahli',
        ],
        request()->routeIs('public.workshop') => [
            'eyebrow' => 'Workshop Vokasi Maritim',
            'title' => 'Membangun Kapal, Membentuk Karakter',
            'description' => 'Workshop perkapalan memadukan keterampilan maritim, kedisiplinan, dan pengalaman praktik.',
            'image' => 'images/workshop/workshop.jpg',
            'alt' => 'Suasana workshop perkapalan',
            'action' => 'Lihat kegiatan',
            'action_url' => '#kegiatan',
        ],
        default => null,
    };

    $videoUrl = trim((string) config('site.showcase_video_url'));
    $youtubeId = null;
    if ($videoUrl !== '' && preg_match('~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:embed/|shorts/|watch\?v=))([A-Za-z0-9_-]{11})~', $videoUrl, $match)) {
        $youtubeId = $match[1];
    }
@endphp

@if ($showcase)
    <section class="page-showcase" data-page-showcase data-auto-advance="{{ $videoUrl !== '' ? 'true' : 'false' }}" aria-label="Sorotan halaman">
        <div class="page-showcase__slides">
            <article class="page-showcase__slide is-active" data-showcase-slide aria-hidden="false">
                <img class="page-showcase__image" src="{{ asset($showcase['image']) }}" alt="{{ $showcase['alt'] }}">
                <div class="page-showcase__shade"></div>
                <div class="page-showcase__copy">
                    <span class="page-showcase__eyebrow">{{ $showcase['eyebrow'] }}</span>
                    <h1>{{ $showcase['title'] }}</h1>
                    <p>{{ $showcase['description'] }}</p>
                    <a class="page-showcase__action" href="{{ $showcase['action_url'] }}">{{ $showcase['action'] }} <span aria-hidden="true">↗</span></a>
                </div>
            </article>

            <article class="page-showcase__slide page-showcase__video" data-showcase-slide aria-hidden="true" inert>
                <img class="page-showcase__video-backdrop" src="{{ asset($showcase['image']) }}" alt="" aria-hidden="true">
                <div class="page-showcase__video-backdrop-shade" aria-hidden="true"></div>
                <div class="page-showcase__video-stage">
                @if ($youtubeId)
                    <iframe
                        class="page-showcase__embed"
                        data-showcase-video
                        data-video-id="{{ $youtubeId }}"
                        data-src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?autoplay=1&amp;mute=1&amp;controls=0&amp;rel=0&amp;playsinline=1&amp;iv_load_policy=3&amp;fs=0&amp;disablekb=1&amp;loop=1&amp;playlist={{ $youtubeId }}&amp;enablejsapi=1&amp;origin={{ urlencode(request()->getSchemeAndHttpHost()) }}"
                        title="Video Perkapalan Al-Zaytun"
                        allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
                        loading="lazy"></iframe>
                @elseif ($videoUrl !== '')
                    <video class="page-showcase__embed" data-showcase-video data-src="{{ \Illuminate\Support\Str::startsWith($videoUrl, ['http://', 'https://']) ? $videoUrl : asset($videoUrl) }}" controls playsinline preload="none" aria-label="Video Perkapalan Al-Zaytun"></video>
                @else
                    <div class="page-showcase__video-empty">
                        <span aria-hidden="true">▶</span>
                        <p>Video halaman ini akan ditampilkan di sini.</p>
                    </div>
                @endif
                </div>
                @if ($youtubeId || $videoUrl !== '')
                    <div class="page-showcase__video-actions">
                        <button type="button" data-showcase-sound aria-pressed="false" aria-label="Aktifkan suara video">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 9v6h4l5 4V5L8 9H4Zm12.5-.5a5 5 0 0 1 0 7m2.5-9.5a8.5 8.5 0 0 1 0 12"/></svg>
                            <span>Suara</span>
                        </button>
                        <button type="button" data-showcase-expand aria-label="Perbesar video">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4H4v4m12-4h4v4M4 16v4h4m12-4v4h-4M4 4l6 6m10-6-6 6M4 20l6-6m10 6-6-6"/></svg>
                            <span>Perbesar</span>
                        </button>
                    </div>
                @endif
                <div class="page-showcase__video-label"><span>Video</span><strong>{{ $showcase['eyebrow'] }}</strong></div>
            </article>
        </div>

        @if ($youtubeId)
            <dialog class="page-showcase__modal" data-showcase-modal aria-label="Video diperbesar">
                <div class="page-showcase__modal-frame">
                    <button type="button" class="page-showcase__modal-close" data-showcase-close aria-label="Tutup video">&times;</button>
                    <iframe
                        data-showcase-expanded-video
                        data-video-id="{{ $youtubeId }}"
                        data-src="https://www.youtube-nocookie.com/embed/{{ $youtubeId }}?autoplay=1&amp;mute=0&amp;controls=1&amp;rel=0&amp;playsinline=1&amp;enablejsapi=1&amp;origin={{ urlencode(request()->getSchemeAndHttpHost()) }}"
                        title="Video Perkapalan Al-Zaytun, pemutar diperbesar"
                        allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
                        allowfullscreen></iframe>
                </div>
            </dialog>
        @endif

        <div class="page-showcase__controls">
            <div class="page-showcase__dots" aria-label="Slide sorotan">
                <button type="button" class="is-active" data-showcase-dot="0" aria-label="Tampilkan gambar" aria-current="true"></button>
                <button type="button" data-showcase-dot="1" aria-label="Tampilkan video" aria-current="false"></button>
            </div>
            <span class="page-showcase__timer" data-showcase-timer aria-live="off">{{ $videoUrl !== '' ? 'Video otomatis dalam 20 detik' : 'Geser untuk melihat video' }}</span>
            <button class="page-showcase__next" type="button" data-showcase-next aria-label="Geser ke video">
                <span>{{ $videoUrl !== '' ? 'Lihat video' : 'Berikutnya' }}</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h15m-6-6 6 6-6 6"/></svg>
            </button>
        </div>
    </section>
@endif
