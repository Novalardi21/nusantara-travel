@props(['complaints'])
<section id="keluhan" class="scroll-mt-24 rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="text-headline-sm font-bold">Keluhan Masuk</h2>
    <ul class="mt-4 divide-y divide-outline-variant/40">
        @forelse ($complaints as $complaint)
            <li class="py-4">
                <p class="font-semibold">#{{ $complaint->id_keluhan }} · {{ $complaint->j_keluhan }}</p>
                <p class="mt-1 text-body-sm text-on-surface-variant">{{ $complaint->nama ?? 'Member #'.$complaint->id_member }}</p>
                <p class="mt-2 break-words">{{ $complaint->d_keluhan }}</p>
            </li>
        @empty
            <li class="py-4 text-on-surface-variant">Belum ada keluhan.</li>
        @endforelse
    </ul>
</section>
