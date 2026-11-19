<x-layouts.app title="Verifikasi Resi & Tiket" portal="operator" page="verification">
    <div class="mx-auto grid max-w-7xl gap-6">
        <h1 class="text-headline-md font-bold">Verifikasi Resi &amp; Tiket</h1>
        <x-feedback />
        <form method="GET" action="{{ route('operations.verification') }}" class="grid items-end gap-3 rounded-xl bg-white p-5 shadow-sm sm:grid-cols-[minmax(0,1fr)_auto_auto]">
            <label class="grid gap-1.5 font-semibold">Cari Resi atau Penumpang
                <input name="cari" value="{{ request('cari') }}" maxlength="100" class="rounded-lg bg-surface-container-low px-3 py-2.5">
            </label>
            <label class="grid gap-1.5 font-semibold">Status
                <select name="status" class="rounded-lg bg-surface-container-low px-3 py-2.5">
                    @foreach (['Dalam Proses', 'Lunas', 'Ditolak'] as $status)
                        <option @selected($selectedStatus === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
            <button class="action-button" type="submit">Terapkan</button>
        </form>

        <section class="grid gap-4" aria-label="Daftar Antrean Resi">
            <h2 class="text-headline-sm font-bold">Daftar Antrean Resi</h2>
            @forelse ($receipts as $receipt)
                <article class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="break-all text-title-md font-bold">Resi {{ $receipt->no_resi }}</h3>
                            <p class="mt-1 text-body-sm text-on-surface-variant">Pesanan #{{ $receipt->id_pesan }} · {{ $receipt->nama ?? 'Member tidak ditemukan' }}</p>
                        </div>
                        <span class="rounded-full bg-surface-container-low px-3 py-1 text-label-md font-semibold">{{ $receipt->status }}</span>
                    </div>
                    <dl class="mt-4 grid gap-3 text-body-sm sm:grid-cols-2 lg:grid-cols-3">
                        <div><dt class="text-on-surface-variant">Rute</dt><dd>{{ $receipt->asal ?? '–' }} → {{ $receipt->tujuan ?? '–' }} · Kursi {{ $receipt->nomor_kursi }}</dd></div>
                        <div><dt class="text-on-surface-variant">Tarif Pesanan</dt><dd>{{ $receipt->harga === null ? '–' : 'Rp '.number_format($receipt->harga, 0, ',', '.') }}</dd></div>
                        <div><dt class="text-on-surface-variant">Nomor Rekening Pengirim</dt><dd class="break-all font-mono-numeric">{{ $receipt->no_rek }}</dd></div>
                        <div><dt class="text-on-surface-variant">Waktu Transfer</dt><dd>{{ $receipt->tgl_transfer }} {{ substr($receipt->jam_transfer, 0, 5) }} WIB</dd></div>
                    </dl>
                    @if ($receipt->status === 'Dalam Proses')
                        <form action="{{ route('operations.verification.update', $receipt->id_pesan) }}" method="POST" class="mt-5 flex flex-wrap gap-3">
                            @csrf
                            @method('PATCH')
                            <button class="action-button" type="submit" name="keputusan" value="Lunas">Setujui Pembayaran</button>
                            <button class="rounded-lg bg-red-50 px-4 py-2 font-semibold text-red-800 hover:bg-red-100" type="submit" name="keputusan" value="Ditolak">Tolak Konfirmasi</button>
                        </form>
                    @endif
                </article>
            @empty
                <p class="rounded-xl bg-white p-6 text-on-surface-variant">Tidak ada resi dengan filter ini.</p>
            @endforelse
            {{ $receipts->links() }}
        </section>
    </div>
</x-layouts.app>
