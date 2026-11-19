@props(['totalBookings', 'pending', 'paid', 'totalComplaints' => null])
<dl class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach (['Total Pesanan' => $totalBookings, 'Menunggu Verifikasi' => $pending, 'Lunas' => $paid, 'Total Keluhan' => $totalComplaints] as $label => $count)
        @continue($count === null)
        <div class="rounded-xl bg-white p-5 shadow-sm">
            <dt class="text-title-sm font-semibold text-on-surface-variant">{{ $label }}</dt>
            <dd class="mt-2 font-mono-numeric text-headline-md font-bold">{{ $count }}</dd>
        </div>
    @endforeach
</dl>
