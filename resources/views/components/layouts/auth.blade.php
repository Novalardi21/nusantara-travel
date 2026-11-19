@props(['title', 'page'])
<x-layouts.app :$title portal="auth" :$page>
    <header class="border-b border-outline-variant/40 bg-white">
        <div class="mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-8">
            <a href="{{ route('booking') }}" class="flex items-center gap-2 text-title-md font-bold">
                <span class="material-symbols-outlined text-secondary" aria-hidden="true">airport_shuttle</span>
                Nusantara Travel
            </a>
            <a href="{{ route('booking') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-title-sm text-secondary hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_back</span>
                Jadwal Publik
            </a>
        </div>
    </header>
    <main id="main-content" @class(['mx-auto max-w-7xl px-4 py-8 sm:px-8 sm:py-12', 'flex min-h-[calc(100svh-4rem)] items-center' => $page === 'login']) tabindex="-1">
        {{ $slot }}
    </main>
</x-layouts.app>
