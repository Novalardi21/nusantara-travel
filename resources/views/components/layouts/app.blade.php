@props(['title', 'portal' => 'member', 'page'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#131b2e">
    <meta name="description" content="Nusantara Travel — perjalanan antar kota, pemilihan kursi, dan pengelolaan tiket shuttle.">
    <title>{{ $title }} — Nusantara Travel</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@400;500;700&amp;family=Material+Symbols+Outlined:wght@400&amp;display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface font-sans text-body-md text-on-surface antialiased" data-page="{{ $page }}" data-portal="{{ $portal }}">
    <a href="#main-content" class="skip-link">Lewati ke konten</a>
    @if ($portal === 'operator')
        <x-navigation.operator-sidebar />
        <button type="button" class="sidebar-scrim" data-toggle-sidebar aria-label="Tutup menu"></button>
        <div class="operator-shell">
            <x-navigation.operator-header />
            <main id="main-content" class="px-4 py-6 pt-24 md:px-8" tabindex="-1">{{ $slot }}</main>
        </div>
    @elseif ($portal === 'auth')
        {{ $slot }}
    @else
        <x-navigation.member-header />
        <main id="main-content" class="member-main" tabindex="-1">{{ $slot }}</main>
        <x-navigation.footer />
    @endif
</body>
</html>
