<x-layouts.app title="Jadwal & Kursi" portal="member" page="booking">
    <x-booking.route-bar :$origins :$destinations :$filters />
    <div class="mx-auto grid w-full max-w-7xl items-start gap-6 px-4 py-8 sm:px-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <div class="grid gap-5">
            <x-feedback />
            <x-booking.schedules :$schedules :$filters :$selected />
            <x-booking.travel-notes />
        </div>
        <aside id="kursi" class="grid gap-5 lg:sticky lg:top-20">
            <x-booking.passenger />
            <x-booking.seat-picker :$selected :$canBook :$occupied />
        </aside>
    </div>
</x-layouts.app>
