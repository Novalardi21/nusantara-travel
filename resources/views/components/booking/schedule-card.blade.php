@props(['schedule', 'active' => false, 'url'])
<article @class(['flex flex-col justify-between gap-4 rounded-xl border bg-white p-5 shadow-sm sm:flex-row sm:items-center', 'border-secondary' => $active, 'border-outline-variant/35' => ! $active])>
    <div class="flex items-start gap-4">
        <div class="rounded-lg bg-surface-container-low px-3 py-2 text-center font-mono-numeric font-semibold">{{ substr($schedule->jam_berangkat, 0, 5) }}<span class="block text-xs">WIB</span></div>
        <div>
            <h3 class="text-title-md font-bold">{{ $schedule->asal ?? 'Asal tidak tersedia' }} → {{ $schedule->tujuan ?? 'Tujuan tidak tersedia' }}</h3>
            <p class="mt-1 text-body-sm text-on-surface-variant">{{ \Illuminate\Support\Carbon::parse($schedule->tgl_berangkat)->translatedFormat('d M Y') }} · {{ $schedule->jenis_mobil ?? 'Armada belum tersedia' }}</p>
        </div>
    </div>
    <div class="flex items-center justify-between gap-3 sm:flex-col sm:items-end">
        <p class="whitespace-nowrap font-mono-numeric font-semibold">Rp {{ number_format($schedule->harga, 0, ',', '.') }}</p>
        @if ($schedule->tgl_berangkat.' '.$schedule->jam_berangkat > now('Asia/Jakarta')->format('Y-m-d H:i:s'))
            <a href="{{ $url }}" class="rounded-lg bg-surface-container-low px-3 py-2 font-semibold text-secondary hover:bg-surface-container">{{ $active ? 'Jadwal Dipilih' : 'Pilih Kursi' }}</a>
        @else
            <span class="text-body-sm text-on-surface-variant">Sudah berangkat</span>
        @endif
    </div>
</article>
