@php
    $socials = [
        ['key' => 'facebook', 'label' => 'Facebook', 'path' => 'M13.5 21v-7.5H16l.4-2.9h-2.9V8.7c0-.8.2-1.4 1.4-1.4H16V4.7c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4v2H7.2v2.9h2.6V21h3.7Z'],
        ['key' => 'instagram', 'label' => 'Instagram', 'path' => 'M7 2.8h10A4.2 4.2 0 0 1 21.2 7v10a4.2 4.2 0 0 1-4.2 4.2H7A4.2 4.2 0 0 1 2.8 17V7A4.2 4.2 0 0 1 7 2.8Zm0 1.8A2.4 2.4 0 0 0 4.6 7v10A2.4 2.4 0 0 0 7 19.4h10a2.4 2.4 0 0 0 2.4-2.4V7A2.4 2.4 0 0 0 17 4.6H7Zm5 2.1a5.3 5.3 0 1 1 0 10.6 5.3 5.3 0 0 1 0-10.6Zm0 1.8a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm5.5-2.8a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Z'],
        ['key' => 'x', 'label' => 'X', 'path' => 'M18.9 3H22l-6.8 7.8L23.2 21h-6.3L12 14.7 6.5 21H3.4l7.3-8.4L2.8 3h6.5l4.5 5.9L18.9 3Zm-1.1 16.2h1.7L8.3 4.7H6.5l11.3 14.5Z'],
        ['key' => 'tiktok', 'label' => 'TikTok', 'path' => 'M19.6 7.5a7.2 7.2 0 0 1-4.4-1.5v8.2a6.2 6.2 0 1 1-6.2-6.2c.4 0 .8 0 1.2.1v3.4a2.9 2.9 0 1 0 1.7 2.7V2.5h3.4c.2 2.2 1.9 4 4.3 4.2v.8Z'],
        ['key' => 'youtube', 'label' => 'YouTube', 'path' => 'M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2 31 31 0 0 0 .5 12 31 31 0 0 0 1 16.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-4.8 31 31 0 0 0-.5-4.8ZM9.7 15.3V8.7l5.8 3.3-5.8 3.3Z'],
        ['key' => 'whatsapp', 'label' => 'WhatsApp', 'path' => 'M12 2a9.8 9.8 0 0 0-8.4 14.8L2.3 22l5.4-1.4A9.8 9.8 0 1 0 12 2Zm0 1.8a8 8 0 0 1 0 16 8.2 8.2 0 0 1-4.1-1.1l-.5-.3-3.1.8.8-3-.3-.5A8 8 0 0 1 12 3.8Zm-3.1 3c-.2 0-.5.1-.7.4-.2.2-.9.9-.9 2.1 0 1.2.9 2.4 1 2.6.1.2 1.8 2.9 4.4 3.9 2.2.9 2.6.7 3.1.6.5-.1 1.6-.7 1.8-1.3.2-.6.2-1.1.1-1.2-.1-.1-.2-.2-.5-.3l-1.7-.8c-.2-.1-.4-.1-.6.2l-.8 1c-.1.2-.3.2-.6.1-.3-.1-1.2-.4-2.2-1.3-.8-.7-1.3-1.5-1.5-1.8-.2-.3 0-.4.1-.6l.4-.5c.2-.2.2-.3.3-.5.1-.2 0-.4 0-.5l-.8-1.8c-.2-.5-.4-.5-.7-.5h-.6Z'],
    ];
@endphp

<div class="foot-social" aria-label="Media sosial">
    <h3>Ikuti Kami</h3>
    <div class="foot-social-links">
        @foreach ($socials as $social)
            @php
                $url = config('site.social.' . $social['key']);
                $isValidUrl = is_string($url) && filter_var($url, FILTER_VALIDATE_URL) && str_starts_with($url, 'https://');
            @endphp
            @if ($isValidUrl)
                <a class="foot-social-link" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="Buka {{ $social['label'] }} Perkapalan Al-Zaytun" title="{{ $social['label'] }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $social['path'] }}"/></svg>
                </a>
            @else
                <span class="foot-social-link is-unavailable" aria-label="Tautan {{ $social['label'] }} belum diatur" title="Tautan {{ $social['label'] }} belum diatur">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $social['path'] }}"/></svg>
                </span>
            @endif
        @endforeach
    </div>
</div>
