<x-layouts.auth title="Daftar Akun Member" page="register">
    <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(0,0.85fr)] lg:gap-8">
        <section class="rounded-2xl border border-outline-variant/40 bg-white p-6 shadow-sm sm:p-9" aria-labelledby="register-title">
            <h1 id="register-title" class="text-headline-lg font-bold">Daftar Akun Member Baru</h1>
            <p class="mt-2 text-on-surface-variant">Lengkapi data penumpang untuk membuat akun Nusantara Travel.</p>

            <form method="POST" action="{{ route('register.store') }}" class="mt-7 grid gap-6">
                @csrf
                <fieldset class="grid min-w-0 gap-4 sm:grid-cols-2">
                    <legend class="mb-4 border-b border-outline-variant/40 pb-2 text-title-md font-bold">Data Penumpang</legend>
                    <x-auth.field name="nama" label="Nama Lengkap" maxlength="100" autocomplete="name" />
                    <div class="flex flex-col gap-1.5">
                        <label for="jen_kelamin" class="text-title-sm font-semibold">Jenis Kelamin</label>
                        <select id="jen_kelamin" name="jen_kelamin" class="w-full rounded-lg bg-surface-container-low px-3.5 py-3" @error('jen_kelamin') aria-invalid="true" aria-describedby="gender-error" @enderror required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" @selected(old('jen_kelamin') === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jen_kelamin') === 'P')>Perempuan</option>
                        </select>
                        @error('jen_kelamin') <p id="gender-error" class="text-body-sm text-error">{{ $message }}</p> @enderror
                    </div>
                    <x-auth.field name="telepon" label="Nomor WhatsApp / HP" type="tel" maxlength="20" autocomplete="tel" />
                    <x-auth.field name="alamat" label="Alamat Domisili / Kota" maxlength="100" autocomplete="street-address" />
                </fieldset>

                <fieldset class="grid min-w-0 gap-4 sm:grid-cols-2">
                    <legend class="mb-4 border-b border-outline-variant/40 pb-2 text-title-md font-bold">Kredensial Akun</legend>
                    <div class="sm:col-span-2">
                        <x-auth.field name="username" label="Username" minlength="4" maxlength="20" autocomplete="username" hint="Gunakan 4–20 karakter tanpa spasi." />
                    </div>
                    <x-auth.field name="password" label="Kata Sandi" type="password" minlength="8" maxlength="72" autocomplete="new-password" hint="Minimal 8 karakter." />
                    <x-auth.field name="password_confirmation" label="Ulangi Kata Sandi" type="password" maxlength="72" autocomplete="new-password" />
                </fieldset>

                <button type="submit" class="action-button flex items-center justify-center gap-2">
                    Daftar Akun Member <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </button>
            </form>

            <p class="mt-5 text-center text-body-sm text-on-surface-variant">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-secondary hover:underline">Masuk</a>
            </p>
        </section>
        <x-auth.travel-panel registration />
    </div>
</x-layouts.auth>
