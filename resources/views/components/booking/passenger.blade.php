<section {{ $attributes->class(['rounded-2xl bg-white p-6 shadow-md']) }} aria-labelledby="passenger-title">
    <h2 id="passenger-title" class="text-label-sm font-semibold uppercase text-on-surface-variant">Data Pelanggan</h2>
    @auth('member')
        @php($member = auth('member')->user())
        <div class="mt-4 flex items-center gap-3">
            <span class="material-symbols-outlined rounded-full bg-secondary-fixed p-3 text-secondary" aria-hidden="true">person</span>
            <div class="min-w-0">
                <p class="break-words text-title-md font-bold">{{ $member->nama }}</p>
                <p class="text-label-md text-secondary">{{ $member->username }}</p>
            </div>
        </div>
        <p class="mt-4 flex flex-wrap justify-between gap-2 rounded-xl bg-surface-container-low p-4">
            <span class="text-on-surface-variant">Nomor Telepon</span>
            <span class="font-mono-numeric">{{ $member->telepon }}</span>
        </p>
    @else
        <p class="mt-3 text-on-surface-variant">Masuk untuk menampilkan data akun penumpang.</p>
        <a href="{{ route('login') }}" class="mt-3 inline-block font-semibold text-secondary hover:underline">Masuk ke Akun</a>
    @endauth
</section>
