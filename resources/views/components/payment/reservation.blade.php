@props(['booking', 'receipts'])
<section class="grid gap-4 rounded-2xl bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <h2 class="text-title-md font-bold">Pesanan #{{ $booking->id_pesan }}</h2>
        <span class="rounded-full bg-surface-container-low px-3 py-1 text-label-md font-semibold text-secondary">{{ $booking->status }}</span>
    </div>
    <p class="text-headline-sm font-semibold">{{ $booking->asal ?? 'Asal tidak tersedia' }} → {{ $booking->tujuan ?? 'Tujuan tidak tersedia' }}</p>
    <p class="text-on-surface-variant">{{ $booking->tgl_berangkat ?? 'Jadwal tidak tersedia' }} · {{ substr($booking->jam_berangkat ?? '', 0, 5) }} WIB · Kursi {{ $booking->nomor_kursi }}</p>
    @if ($booking->harga !== null)
        <p class="text-title-md font-semibold">Rp {{ number_format($booking->harga, 0, ',', '.') }}</p>
    @endif
    @if ($booking->status === 'Ditolak')
        <p class="rounded-lg bg-red-50 p-3 text-red-900">Petugas menolak konfirmasi transfer. Hubungi loket bila perlu memastikan detail pembayaran.</p>
    @endif
    @if ($receipts->isNotEmpty())
        <ul class="grid gap-2 border-t border-outline-variant/40 pt-4 text-body-sm">
            @foreach ($receipts as $receipt)
                <li>Resi {{ $receipt->no_resi }} · {{ $receipt->tgl_transfer }} {{ substr($receipt->jam_transfer, 0, 5) }} WIB</li>
            @endforeach
        </ul>
    @endif
</section>
