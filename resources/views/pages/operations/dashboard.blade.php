<x-layouts.app title="Dashboard Operasional" portal="operator" page="dashboard">
    <div class="mx-auto grid max-w-7xl gap-6">
        <header>
            <h1 class="text-headline-md font-bold">Ringkasan Operasional &amp; Tiket</h1>
            <p class="mt-2 text-on-surface-variant">{{ Gate::allows('manage-operations') ? 'Kelola jadwal keberangkatan dan pantau keluhan pelanggan.' : 'Lihat jadwal, manifest penumpang, dan status pembayaran.' }}</p>
        </header>
        <x-feedback />
        <x-operations.metrics :$totalBookings :$pending :$paid :total-complaints="$totalComplaints ?? null" />
        <x-operations.fleet-table :$schedules />
        @can('manage-operations')
        <section id="tambah-jadwal" class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-headline-sm font-bold">Tambah Jadwal Keberangkatan</h2>
            <form action="{{ route('operations.schedules.store') }}" method="POST" class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @csrf
                <label class="grid gap-1.5 font-semibold">Kota Asal
                    <select name="id_kota_asal" class="rounded-lg bg-surface-container-low px-3 py-3" required>
                        <option value="">Pilih kota asal</option>
                        @foreach ($origins as $origin)
                            <option value="{{ $origin->id_kota_asal }}" @selected(old('id_kota_asal') == $origin->id_kota_asal)>{{ $origin->nm_kota_asal }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 font-semibold">Kota Tujuan
                    <select name="id_kota_tujuan" class="rounded-lg bg-surface-container-low px-3 py-3" required>
                        <option value="">Pilih kota tujuan</option>
                        @foreach ($destinations as $destination)
                            <option value="{{ $destination->id_kota_tujuan }}" @selected(old('id_kota_tujuan') == $destination->id_kota_tujuan)>{{ $destination->nm_kota_tujuan }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="grid gap-1.5 font-semibold">Armada
                    <select name="id_mobil" class="rounded-lg bg-surface-container-low px-3 py-3" required>
                        <option value="">Pilih kendaraan</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id_mobil }}" @selected(old('id_mobil') == $vehicle->id_mobil)>{{ $vehicle->jenis_mobil }} · {{ $vehicle->nomor_polisi }}</option>
                        @endforeach
                    </select>
                </label>
                <x-auth.field name="tgl_berangkat" label="Tanggal Berangkat" type="date" :min="now('Asia/Jakarta')->toDateString()" />
                <x-auth.field name="jam_berangkat" label="Jam Berangkat (WIB)" type="time" />
                <x-auth.field name="harga" label="Tarif per Kursi (Rp)" type="number" min="1" max="2147483647" step="1" />
                <div class="md:col-span-2 xl:col-span-3"><button class="action-button" type="submit">Simpan Jadwal</button></div>
            </form>
        </section>
        <x-operations.complaints :$complaints />
        @endcan
    </div>
</x-layouts.app>
