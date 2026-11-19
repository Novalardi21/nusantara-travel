@props(['booking'])
<section class="rounded-2xl bg-white p-6 shadow-sm sm:p-8">
    <h2 class="text-headline-sm font-bold">Detail Transfer</h2>
    <p class="mt-2 text-body-sm text-on-surface-variant">Konfirmasi rekening tujuan dengan loket sebelum melakukan transfer. Nomor resi internal dibuat setelah konfirmasi dikirim.</p>
    <form method="POST" action="{{ route('payment.store', $booking->id_pesan, absolute: false) }}" class="mt-5 grid gap-4 sm:grid-cols-2">
        @csrf
        <x-auth.field name="no_rek" label="Nomor Rekening Pengirim" maxlength="30" inputmode="numeric" pattern="[0-9]{1,30}" />
        <x-auth.field name="tgl_transfer" label="Tanggal Transfer" type="date" :max="now('Asia/Jakarta')->toDateString()" />
        <x-auth.field name="jam_transfer" label="Jam Transfer (WIB)" type="time" />
        <div class="sm:col-span-2"><button type="submit" class="action-button">Kirim Konfirmasi</button></div>
    </form>
</section>
