<section id="keluhan" class="scroll-mt-32 rounded-2xl bg-white p-6 shadow-sm">
    <h2 class="text-headline-sm font-bold">Keluhan Perjalanan</h2>
    <form action="{{ route('complaints.store') }}" method="POST" class="mt-5 grid gap-4">
        @csrf
        <label class="grid gap-1.5 font-semibold">Jenis Keluhan
            <select name="j_keluhan" class="rounded-lg bg-surface-container-low px-3 py-3" required>
                <option value="">Pilih jenis keluhan</option>
                @foreach (['Jadwal', 'Pembayaran', 'Armada', 'Layanan', 'Lainnya'] as $type)
                    <option @selected(old('j_keluhan') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </label>
        <label class="grid gap-1.5 font-semibold">Detail Keluhan
            <textarea name="d_keluhan" rows="3" maxlength="200" class="rounded-lg bg-surface-container-low px-3 py-3" required>{{ old('d_keluhan') }}</textarea>
        </label>
        <div><button type="submit" class="action-button">Kirim Keluhan</button></div>
    </form>
</section>
