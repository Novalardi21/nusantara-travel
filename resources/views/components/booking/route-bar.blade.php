@props(['origins', 'destinations', 'filters'])
<section class="bg-surface-container-low" id="pencarian">
    <div class="mx-auto grid max-w-7xl gap-4 px-4 py-6 sm:px-8">
        <h1 class="text-headline-md font-bold">Cari Jadwal Perjalanan</h1>
        <form method="GET" action="{{ route('booking.schedules') }}" class="grid items-end gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_auto]">
            <label class="grid gap-1.5 font-semibold">Kota Asal
                <select name="asal" class="w-full rounded-lg bg-white px-3 py-2.5">
                    <option value="">Semua kota asal</option>
                    @foreach ($origins as $origin)
                        <option value="{{ $origin->id_kota_asal }}" @selected(($filters['asal'] ?? null) == $origin->id_kota_asal)>{{ $origin->nm_kota_asal }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1.5 font-semibold">Kota Tujuan
                <select name="tujuan" class="w-full rounded-lg bg-white px-3 py-2.5">
                    <option value="">Semua kota tujuan</option>
                    @foreach ($destinations as $destination)
                        <option value="{{ $destination->id_kota_tujuan }}" @selected(($filters['tujuan'] ?? null) == $destination->id_kota_tujuan)>{{ $destination->nm_kota_tujuan }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-1.5 font-semibold">Tanggal Keberangkatan
                <input type="date" name="tanggal" value="{{ $filters['tanggal'] ?? '' }}" class="w-full rounded-lg bg-white px-3 py-2.5">
            </label>
            <button type="submit" class="action-button">Cari Jadwal</button>
        </form>
    </div>
</section>
