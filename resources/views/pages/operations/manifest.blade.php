<x-layouts.app title="Manifest Penumpang" portal="operator" page="manifest">
    <section class="rounded-2xl bg-white p-6 shadow-sm print:shadow-none">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-headline-md font-bold">Manifest Penumpang</h1>
                <p class="mt-2">{{ $schedule->asal ?? '–' }} → {{ $schedule->tujuan ?? '–' }} · {{ $schedule->tgl_berangkat }} {{ substr($schedule->jam_berangkat, 0, 5) }} WIB</p>
                <p class="text-on-surface-variant">{{ $schedule->jenis_mobil }} · {{ $schedule->nomor_polisi }}</p>
            </div>
            <button class="action-button print:hidden" type="button" data-print>Cetak Manifest</button>
        </div>
        <ul class="mt-5 divide-y divide-outline-variant/40">
            @forelse ($passengers as $passenger)
                <li class="grid gap-1 py-3 sm:grid-cols-4">
                    <span>Kursi {{ $passenger->nomor_kursi }}</span>
                    <span class="font-semibold">{{ $passenger->nama ?? 'Member tidak ditemukan' }}</span>
                    <span>{{ $passenger->telepon ?? '–' }}</span>
                    <span>{{ $passenger->status }}</span>
                </li>
            @empty
                <li class="py-4">Belum ada penumpang.</li>
            @endforelse
        </ul>
    </section>
</x-layouts.app>
