<x-layouts.app title="Profil Member" portal="member" page="profile">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-8 lg:grid-cols-[18rem_minmax(0,1fr)]">
        <aside class="grid content-start gap-4">
            <section class="rounded-2xl bg-primary-container p-6 text-white">
                <span class="material-symbols-outlined rounded-full bg-white/15 p-3" aria-hidden="true">person</span>
                <h1 class="mt-4 break-words text-headline-md font-bold">{{ $member->nama }}</h1>
                <p class="mt-1 text-secondary-fixed">{{ '@'.$member->username }}</p>
            </section>
            <nav aria-label="Navigasi akun" class="grid gap-1 rounded-2xl bg-white p-3 shadow-sm">
                <a class="nav-link" href="#data-diri">Data Diri</a>
                <a class="nav-link" href="#keamanan">Keamanan &amp; Kata Sandi</a>
                <a class="nav-link" href="{{ route('payment') }}#tiket-saya">Riwayat Tiket</a>
                <a class="nav-link" href="{{ route('payment') }}#keluhan">Pusat Keluhan</a>
            </nav>
        </aside>
        <div class="grid gap-6">
            <x-feedback />
            <section id="data-diri" class="scroll-mt-32 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-headline-sm font-bold">Data Diri Penumpang</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
                    @csrf
                    @method('PATCH')
                    <x-auth.field name="nama" label="Nama Lengkap" :value="$member->nama" maxlength="100" autocomplete="name" />
                    <label class="grid gap-1.5 text-title-sm font-semibold" for="jen_kelamin">Jenis Kelamin
                        <select id="jen_kelamin" name="jen_kelamin" class="w-full rounded-lg bg-surface-container-low px-3 py-3" required>
                            <option value="" @selected(! old('jen_kelamin', $member->jen_kelamin))>Pilih jenis kelamin</option>
                            <option value="L" @selected(old('jen_kelamin', $member->jen_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jen_kelamin', $member->jen_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </label>
                    <x-auth.field name="telepon" label="Nomor Telepon" :value="$member->telepon" type="tel" maxlength="20" autocomplete="tel" />
                    <x-auth.field name="alamat" label="Alamat Domisili" :value="$member->alamat" maxlength="100" autocomplete="street-address" />
                    <div class="sm:col-span-2"><button class="action-button" type="submit">Simpan Data Diri</button></div>
                </form>
            </section>
            <section id="keamanan" class="scroll-mt-32 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
                <h2 class="text-headline-sm font-bold">Keamanan &amp; Kata Sandi</h2>
                <p class="mt-2 text-on-surface-variant">Username akun <strong class="text-on-surface">{{ $member->username }}</strong></p>
                <form method="POST" action="{{ route('profile.password') }}" class="mt-5 grid gap-4 sm:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <div class="sm:col-span-2"><x-auth.field name="current_password" label="Kata Sandi Saat Ini" type="password" autocomplete="current-password" /></div>
                    <x-auth.field name="password" label="Kata Sandi Baru" type="password" minlength="8" maxlength="72" autocomplete="new-password" />
                    <x-auth.field name="password_confirmation" label="Ulangi Kata Sandi Baru" type="password" maxlength="72" autocomplete="new-password" />
                    <div class="sm:col-span-2"><button class="action-button" type="submit">Perbarui Kata Sandi</button></div>
                </form>
            </section>
        </div>
    </div>
</x-layouts.app>
