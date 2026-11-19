@props(['registration' => false])
<aside class="flex flex-col overflow-hidden rounded-2xl bg-primary-container text-white">
    <div class="relative isolate flex min-h-56 grow flex-col justify-end overflow-hidden bg-secondary px-6 py-7 sm:px-8">
        <img class="absolute inset-0 -z-20 h-full w-full object-cover" src="{{ asset($registration ? 'images/auth-register.jpg' : 'images/auth-login.jpg') }}" alt="">
        <div class="absolute inset-0 -z-10 bg-linear-to-t from-primary-container via-primary-container/45 to-primary-container/10"></div>
        <p class="text-label-md uppercase tracking-widest text-secondary-fixed">Nusantara Travel</p>
        <h2 class="mt-2 text-headline-md font-bold">{{ $registration ? 'Perjalanan Anda Dimulai di Sini' : 'Shuttle Antar Kota' }}</h2>
    </div>
    <div class="grid gap-5 p-6 sm:p-8">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-secondary-fixed" aria-hidden="true">airline_seat_recline_extra</span>
            <div><h3 class="text-title-sm font-semibold">Pilih jadwal dan kursi</h3><p class="mt-1 text-body-sm text-surface-variant">Temukan waktu keberangkatan dan tempat duduk yang sesuai.</p></div>
        </div>
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-secondary-fixed" aria-hidden="true">confirmation_number</span>
            <div><h3 class="text-title-sm font-semibold">Akses tiket dari satu tempat</h3><p class="mt-1 text-body-sm text-surface-variant">Lihat riwayat pemesanan dan status pembayaran Anda.</p></div>
        </div>
    </div>
</aside>
