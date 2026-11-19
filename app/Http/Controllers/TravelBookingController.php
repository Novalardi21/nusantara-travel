<?php

namespace App\Http\Controllers;

use App\Services\TravelQueries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TravelBookingController extends Controller
{
    public function index(Request $request, TravelQueries $travel): View
    {
        $filters = $request->validate([
            'asal' => ['nullable', 'integer'],
            'tujuan' => ['nullable', 'integer'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'jadwal' => ['nullable', 'integer'],
        ]);
        $schedules = $travel->schedules()
            ->when($filters['asal'] ?? null, fn ($query, $id) => $query->where('j.id_kota_asal', $id))
            ->when($filters['tujuan'] ?? null, fn ($query, $id) => $query->where('j.id_kota_tujuan', $id))
            ->when($filters['tanggal'] ?? null, fn ($query, $date) => $query->where('j.tgl_berangkat', $date))
            ->orderByRaw('CASE WHEN j.tgl_berangkat >= ? THEN 0 ELSE 1 END', [now('Asia/Jakarta')->toDateString()])
            ->orderBy('j.tgl_berangkat')->orderBy('j.jam_berangkat')->orderBy('j.id_jadwal')
            ->paginate(12)->withQueryString();
        $selected = isset($filters['jadwal'])
            ? $travel->schedules()->where('j.id_jadwal', $filters['jadwal'])->first()
            : null;

        return view('pages.member.booking', [
            'schedules' => $schedules,
            'origins' => DB::table('kota_asal')->orderBy('nm_kota_asal')->get(),
            'destinations' => DB::table('kota_tujuan')->orderBy('nm_kota_tujuan')->get(),
            'selected' => $selected,
            'canBook' => $selected && $travel->isUpcoming($selected),
            'occupied' => $selected ? $travel->occupiedSeats()->where('id_jadwal', $selected->id_jadwal)->pluck('nomor_kursi')->map(fn ($seat) => (string) $seat)->all() : [],
            'filters' => $filters,
        ]);
    }

    public function store(Request $request, TravelQueries $travel): RedirectResponse
    {
        $input = $request->validate([
            'id_jadwal' => ['required', 'integer'],
            'nomor_kursi' => ['required', Rule::in(['1', '2', '3', '4', '5'])],
        ]);

        $bookingId = DB::transaction(function () use ($input, $request, $travel): int {
            $schedule = DB::table('jadwal')->where('id_jadwal', $input['id_jadwal'])->lockForUpdate()->first();

            if (! $schedule || ! $travel->isUpcoming($schedule)) {
                throw ValidationException::withMessages(['id_jadwal' => 'Jadwal keberangkatan sudah ditutup atau tidak tersedia.']);
            }

            if ($travel->occupiedSeats()->where('id_jadwal', $schedule->id_jadwal)->where('nomor_kursi', $input['nomor_kursi'])->exists()) {
                throw ValidationException::withMessages(['nomor_kursi' => 'Kursi sudah terisi. Silakan pilih kursi lain.']);
            }

            return DB::table('pesan')->insertGetId([
                'id_jadwal' => $schedule->id_jadwal,
                'nomor_kursi' => $input['nomor_kursi'],
                'id_member' => $request->user('member')->getAuthIdentifier(),
                'status' => 'Belum Bayar',
            ], 'id_pesan');
        }, 3);

        return redirect()->route('payment', ['pesanan' => $bookingId])->with('status', 'Kursi berhasil dipesan.');
    }
}
