<x-layouts.app title="E-Ticket" portal="member" page="ticket">
    <article class="mx-auto my-8 grid max-w-xl gap-5 rounded-2xl bg-white p-6 shadow-sm sm:p-8 print:shadow-none">
        <div class="flex items-center justify-between gap-3">
            <p class="font-semibold text-secondary">Nusantara Travel</p>
            <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-800">Lunas</span>
        </div>
        <h1 class="text-headline-md font-bold">E-Ticket #{{ $ticket->id_pesan }}</h1>
        <dl class="grid gap-3 border-t border-dashed border-outline-variant py-4">
            <div><dt class="text-body-sm text-on-surface-variant">Penumpang</dt><dd class="font-semibold">{{ $member->nama }}</dd></div>
            <div><dt class="text-body-sm text-on-surface-variant">Rute</dt><dd class="font-semibold">{{ $ticket->asal ?? 'Asal tidak tersedia' }} → {{ $ticket->tujuan ?? 'Tujuan tidak tersedia' }}</dd></div>
            <div><dt class="text-body-sm text-on-surface-variant">Keberangkatan</dt><dd>{{ $ticket->tgl_berangkat ?? 'Jadwal tidak tersedia' }} {{ substr($ticket->jam_berangkat ?? '', 0, 5) }} WIB</dd></div>
            <div><dt class="text-body-sm text-on-surface-variant">Armada &amp; Kursi</dt><dd>{{ $ticket->jenis_mobil ?? 'Armada tidak tersedia' }} · {{ $ticket->nomor_polisi }} · Kursi {{ $ticket->nomor_kursi }}</dd></div>
        </dl>
        <button class="action-button print:hidden" type="button" data-print>Cetak Tiket</button>
    </article>
</x-layouts.app>
