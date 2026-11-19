@props(['schedules'])
<section id="jadwal-armada" class="scroll-mt-24 rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="text-headline-sm font-bold">Jadwal &amp; Armada</h2>
    <div class="mt-4 overflow-x-auto" tabindex="0" role="region" aria-label="Daftar jadwal armada">
        <table class="w-full text-left text-body-sm">
            <thead class="bg-surface-container-low text-on-surface-variant">
                <tr>
                    @foreach (['Rute', 'Keberangkatan', 'Armada', 'Terisi', 'Tarif', 'Manifest'] as $column)
                        <th scope="col" class="px-3 py-3">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/35">
                @forelse ($schedules as $schedule)
                    <tr>
                        <td class="px-3 py-3 font-semibold">{{ $schedule->asal ?? '–' }} → {{ $schedule->tujuan ?? '–' }}</td>
                        <td class="px-3 py-3">{{ $schedule->tgl_berangkat }} · {{ substr($schedule->jam_berangkat, 0, 5) }} WIB</td>
                        <td class="px-3 py-3">{{ $schedule->jenis_mobil ?? '–' }} · {{ $schedule->nomor_polisi ?? '–' }}</td>
                        <td class="px-3 py-3">{{ $schedule->occupied }} / 5</td>
                        <td class="px-3 py-3">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</td>
                        <td class="px-3 py-3"><a class="font-semibold text-secondary hover:underline" href="{{ route('operations.manifest', $schedule->id_jadwal) }}">Lihat Manifest</a></td>
                    </tr>
                @empty
                    <tr><td class="px-3 py-5" colspan="6">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $schedules->links() }}</div>
</section>
