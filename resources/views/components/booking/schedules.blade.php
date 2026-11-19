@props(['schedules', 'filters', 'selected'])
<section class="grid gap-4" aria-labelledby="schedules-title">
    <h2 id="schedules-title" class="text-headline-sm font-bold">Jadwal Keberangkatan</h2>
    @forelse ($schedules as $schedule)
        <x-booking.schedule-card :$schedule :active="$selected?->id_jadwal === $schedule->id_jadwal" :url="route('booking.schedules', [...$filters, 'jadwal' => $schedule->id_jadwal, 'page' => $schedules->currentPage()]).'#kursi'" />
    @empty
        <p class="rounded-xl bg-white p-6 text-on-surface-variant">Belum ada jadwal untuk pencarian ini.</p>
    @endforelse
    {{ $schedules->links() }}
</section>
