{{-- Latar belakang laut animasi: dipakai di layouts/app.blade.php. Murni dekoratif (aria-hidden). --}}
<div class="fluid-bg sea-bg" aria-hidden="true">
    <span class="blob b1"></span>
    <span class="blob b2"></span>
    <span class="blob b3"></span>
    <span class="blob b4"></span>
    <span class="blob b5"></span>

    <svg class="gull gull-1" viewBox="0 0 40 14" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round"><path d="M2 11 Q10 1 20 10 Q30 1 38 11"/></svg>
    <svg class="gull gull-2" viewBox="0 0 40 14" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round"><path d="M2 11 Q10 1 20 10 Q30 1 38 11"/></svg>
    <svg class="gull gull-3" viewBox="0 0 40 14" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round"><path d="M2 11 Q10 1 20 10 Q30 1 38 11"/></svg>

    <div class="wave-layer wave-back">
        <svg viewBox="0 0 2880 120" preserveAspectRatio="none"><path d="M0 60 C360 0 360 0 720 60 C1080 120 1080 120 1440 60 C1800 0 1800 0 2160 60 C2520 120 2520 120 2880 60 V120 H0 Z"/></svg>
    </div>

    <div class="ship ship-sail">
        <div class="ship-bob">
            <svg viewBox="0 0 160 150">
                <path d="M82 12 L82 102 L138 102 Z" fill="#ffffff" fill-opacity=".92"/>
                <path d="M75 24 L75 102 L26 102 Z" fill="#e6f6ff" fill-opacity=".9"/>
                <rect x="78" y="8" width="3.2" height="98" fill="#0b2a5c" fill-opacity=".85"/>
                <path d="M81 8 L98 13 L81 18 Z" fill="#f59e0b"/>
                <path d="M8 106 L152 106 L134 132 Q80 141 26 132 Z" fill="#0b2a5c" fill-opacity=".88"/>
                <path d="M14 112 L146 112" stroke="#38bdf8" stroke-width="2.4" stroke-opacity=".9"/>
            </svg>
        </div>
    </div>

    <div class="ship ship-cargo">
        <div class="ship-bob">
            <svg viewBox="0 0 300 120">
                <rect x="30" y="48" width="38" height="24" rx="2" fill="#0d9488" fill-opacity=".9"/>
                <rect x="70" y="48" width="38" height="24" rx="2" fill="#f59e0b" fill-opacity=".9"/>
                <rect x="110" y="48" width="38" height="24" rx="2" fill="#2563eb" fill-opacity=".9"/>
                <rect x="150" y="48" width="38" height="24" rx="2" fill="#ea580c" fill-opacity=".9"/>
                <rect x="50" y="24" width="38" height="24" rx="2" fill="#2563eb" fill-opacity=".85"/>
                <rect x="90" y="24" width="38" height="24" rx="2" fill="#0d9488" fill-opacity=".85"/>
                <rect x="232" y="26" width="38" height="46" rx="2" fill="#f8fafc" fill-opacity=".95"/>
                <rect x="226" y="18" width="50" height="10" rx="2" fill="#0b2a5c" fill-opacity=".85"/>
                <rect x="243" y="6" width="9" height="14" fill="#ef4444"/>
                <rect x="237" y="34" width="28" height="5" fill="#38bdf8" fill-opacity=".8"/>
                <path d="M6 72 L294 72 L268 106 L34 106 Z" fill="#0b2a5c" fill-opacity=".9"/>
                <path d="M12 80 L288 80" stroke="#ef4444" stroke-width="3" stroke-opacity=".9"/>
            </svg>
        </div>
    </div>

    <div class="wave-layer wave-mid">
        <svg viewBox="0 0 2880 120" preserveAspectRatio="none"><path d="M0 60 C360 0 360 0 720 60 C1080 120 1080 120 1440 60 C1800 0 1800 0 2160 60 C2520 120 2520 120 2880 60 V120 H0 Z"/></svg>
    </div>
    <div class="wave-layer wave-front">
        <svg viewBox="0 0 2880 120" preserveAspectRatio="none"><path d="M0 60 C360 0 360 0 720 60 C1080 120 1080 120 1440 60 C1800 0 1800 0 2160 60 C2520 120 2520 120 2880 60 V120 H0 Z"/></svg>
    </div>
</div>
