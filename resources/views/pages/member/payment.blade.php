<x-layouts.app title="Konfirmasi Pembayaran" portal="member" page="payment">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-8">
        <div>
            <h1 class="text-headline-md font-bold">Konfirmasi Pembayaran</h1>
            <p class="mt-2 text-on-surface-variant">Kirim detail transfer agar petugas dapat memeriksa pembayaran pesanan Anda.</p>
        </div>
        <x-feedback />
        @if ($booking)
            <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.35fr)]">
                <x-payment.reservation :$booking :$receipts />
                @if ($canConfirm)
                    <x-payment.confirmation-form :$booking />
                @endif
            </div>
        @else
            <p class="rounded-xl bg-white p-6 text-on-surface-variant">Belum ada pesanan. <a href="{{ route('booking') }}" class="font-semibold text-secondary hover:underline">Cari jadwal perjalanan</a>.</p>
        @endif
        <x-payment.ticket-history :$bookings />
        <x-payment.support />
    </div>
</x-layouts.app>
