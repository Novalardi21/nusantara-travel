<header {{ $attributes->class(['member-header fixed top-0 inset-x-0 z-40 bg-white/95 backdrop-blur-xl shadow-sm']) }}>
    <div class="h-16 max-w-7xl mx-auto px-4 xl:px-12 flex items-center justify-between gap-4">
        <a href="{{ route('booking') }}" class="flex min-w-0 items-center gap-2" aria-label="Nusantara Travel, beranda">
            <span class="material-symbols-outlined text-secondary text-[30px]">airport_shuttle</span>
            <span class="flex flex-col"><strong class="text-title-md tracking-tight">Nusantara Travel</strong><span class="text-[10px] uppercase tracking-widest text-on-surface-variant">Executive Shuttle</span></span>
        </a>
        <nav class="member-nav" aria-label="Navigasi pelanggan">
            <a href="{{ route('booking') }}#pencarian" class="nav-link" data-open-search>Cari Tiket</a>
            <a href="{{ route('booking.schedules') }}" @class(['nav-link', 'is-active' => request()->routeIs('booking*')]) @if(request()->routeIs('booking*')) aria-current="page" @endif>Jadwal &amp; Kursi</a>
            <a href="{{ route('payment') }}" @class(['nav-link', 'is-active' => request()->routeIs('payment')]) @if(request()->routeIs('payment')) aria-current="page" @endif>Konfirmasi Bayar</a>
            <a href="{{ route('payment') }}#tiket-saya" class="nav-link">Tiket &amp; Bantuan</a>
        </nav>
        <div class="flex items-center gap-3 shrink-0">
            @auth('member')
                <a href="{{ route('profile') }}" class="rounded-lg px-2 py-2 text-title-sm font-semibold text-secondary hover:bg-surface-container-low">Profil</a>
            @endauth
            @if (auth('member')->check() || auth('admin')->check() || auth('operator')->check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-lg px-3 py-2 text-title-sm text-secondary hover:bg-surface-container-low">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-title-sm text-secondary hover:bg-surface-container-low">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-secondary px-3 py-2 text-title-sm font-semibold text-white">Daftar</a>
            @endif
        </div>
    </div>
</header>
