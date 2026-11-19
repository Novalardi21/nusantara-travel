<x-layouts.auth title="Masuk" page="login">
    <div class="grid w-full items-stretch gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)] lg:gap-8">
        <section class="rounded-2xl border border-outline-variant/40 bg-white p-6 shadow-sm sm:p-9" aria-labelledby="login-title">
            <span class="material-symbols-outlined rounded-xl bg-surface-container-low p-3 text-secondary" aria-hidden="true">lock</span>
            <h1 id="login-title" class="mt-4 text-headline-lg font-bold">Masuk ke Akun Anda</h1>
            <p class="mt-2 text-on-surface-variant">Gunakan username atau email yang terdaftar di Nusantara Travel.</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-7 grid gap-5">
                @csrf
                <x-auth.field name="identifier" label="Username atau Email" autocomplete="username" maxlength="50" />
                <x-auth.field name="password" label="Kata Sandi" type="password" autocomplete="current-password" maxlength="255" />
                <button type="submit" class="action-button flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" aria-hidden="true">login</span> Masuk
                </button>
            </form>

            <p class="mt-6 rounded-xl bg-surface-container-low px-4 py-3 text-body-sm text-on-surface-variant">
                Belum punya akun penumpang? <a href="{{ route('register') }}" class="font-bold text-secondary hover:underline">Daftar akun member</a>
            </p>
        </section>
        <x-auth.travel-panel />
    </div>
</x-layouts.auth>
