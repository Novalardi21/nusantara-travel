@props(['bookings'])
<section id="tiket-saya" class="scroll-mt-32 rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="text-headline-sm font-bold">Riwayat Reservasi &amp; Tiket Saya</h2>
    <ul class="mt-4 divide-y divide-outline-variant/40">
        @forelse ($bookings as $item)
            <li class="flex flex-wrap items-center justify-between gap-3 py-4">
                <div>
                    <a class="text-title-sm font-semibold text-secondary hover:underline" href="{{ route('payment', ['pesanan' => $item->id_pesan]) }}">#{{ $item->id_pesan }} · {{ $item->asal ?? 'Asal tidak tersedia' }} → {{ $item->tujuan ?? 'Tujuan tidak tersedia' }}</a>
                    <p class="mt-1 text-body-sm text-on-surface-variant">{{ $item->tgl_berangkat ?? 'Jadwal tidak tersedia' }} · Kursi {{ $item->nomor_kursi }} · {{ $item->status }}</p>
                </div>
                @if ($item->status === 'Lunas')
                    <a href="{{ route('ticket', $item->id_pesan) }}" class="rounded-lg bg-surface-container-low px-3 py-2 font-semibold text-secondary">Lihat E-Ticket</a>
                @endif
            </li>
        @empty
            <li class="py-4 text-on-surface-variant">Belum ada tiket.</li>
        @endforelse
    </ul>
    {{ $bookings->links() }}
</section>
