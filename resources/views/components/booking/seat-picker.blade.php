@props(['selected', 'canBook', 'occupied'])
<section class="rounded-2xl bg-white p-6 shadow-sm" aria-labelledby="seat-title">
    <h2 id="seat-title" class="text-title-md font-bold">Pilih Kursi</h2>
    @if ($canBook)
        <p class="mt-2 text-body-sm text-on-surface-variant">{{ $selected->asal }} → {{ $selected->tujuan }} · {{ $selected->tgl_berangkat }}, {{ substr($selected->jam_berangkat, 0, 5) }} WIB</p>
        <form action="{{ route('booking.store') }}" method="POST" class="mt-5 grid gap-5">
            @csrf
            <input type="hidden" name="id_jadwal" value="{{ $selected->id_jadwal }}">
            <fieldset class="grid grid-cols-2 gap-3 rounded-xl bg-surface-container-low p-4">
                <legend class="text-body-sm font-semibold">Denah Shuttle</legend>
                @foreach ([1, 'driver', 2, 3, 4, 5] as $seat)
                    @if ($seat === 'driver')
                        <span class="rounded-lg bg-surface-container p-3 text-center text-body-sm font-semibold">Sopir</span>
                    @else
                        @php($taken = in_array((string) $seat, $occupied, true))
                        <label class="flex items-center justify-center gap-2 rounded-lg border border-outline-variant/50 bg-white px-2 py-3 has-checked:border-secondary has-checked:bg-secondary-fixed has-disabled:bg-surface-container has-disabled:text-on-surface-variant">
                            <input type="radio" name="nomor_kursi" value="{{ $seat }}" @disabled($taken) @checked(! $taken && old('nomor_kursi') == $seat) required>
                            <span class="text-body-sm font-semibold">{{ $seat }}{{ $taken ? ' · Terisi' : '' }}</span>
                        </label>
                    @endif
                @endforeach
            </fieldset>
            <p class="flex justify-between gap-2 font-semibold"><span>Total</span><span>Rp {{ number_format($selected->harga, 0, ',', '.') }}</span></p>
            @auth('member')
                <button class="action-button" type="submit" @disabled(count(array_unique($occupied)) >= 5)>Pesan Kursi</button>
            @else
                <a class="action-button text-center" href="{{ route('login') }}">Masuk untuk Memesan</a>
            @endauth
        </form>
    @else
        <p class="mt-2 text-on-surface-variant">Pilih jadwal keberangkatan yang masih tersedia untuk melihat denah kursi.</p>
    @endif
</section>
