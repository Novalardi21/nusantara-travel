<?php

namespace App\Http\Controllers;

use App\Services\TravelQueries;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TravelOperationsController extends Controller
{
    public function index(TravelQueries $travel): View
    {
        $data = [
            'schedules' => $travel->schedules()
                ->addSelect(['occupied' => $travel->occupiedSeats()->selectRaw('COUNT(DISTINCT nomor_kursi)')->whereColumn('id_jadwal', 'j.id_jadwal')])
                ->orderByDesc('j.tgl_berangkat')->orderByDesc('j.jam_berangkat')->orderByDesc('j.id_jadwal')->paginate(12),
            'totalBookings' => DB::table('pesan')->count(),
            'pending' => DB::table('pesan')->where('status', 'Dalam Proses')->count(),
            'paid' => DB::table('pesan')->where('status', 'Lunas')->count(),
        ];

        if (Gate::allows('manage-operations')) {
            $data += [
                'origins' => DB::table('kota_asal')->orderBy('nm_kota_asal')->get(),
                'destinations' => DB::table('kota_tujuan')->orderBy('nm_kota_tujuan')->get(),
                'vehicles' => DB::table('kendaraan')->orderBy('jenis_mobil')->get(),
                'complaints' => DB::table('keluhan as k')->leftJoin('member as m', 'm.id_member', '=', 'k.id_member')
                    ->select('k.*', 'm.nama')->orderByDesc('k.id_keluhan')->limit(10)->get(),
                'totalComplaints' => DB::table('keluhan')->count(),
            ];
        }

        return view('pages.operations.dashboard', $data);
    }

    public function storeSchedule(Request $request, TravelQueries $travel): RedirectResponse
    {
        $input = $request->validate([
            'id_kota_asal' => ['required', 'integer', Rule::exists('kota_asal', 'id_kota_asal')],
            'id_kota_tujuan' => ['required', 'integer', Rule::exists('kota_tujuan', 'id_kota_tujuan')],
            'id_mobil' => ['required', 'integer', Rule::exists('kendaraan', 'id_mobil')],
            'tgl_berangkat' => ['required', 'date_format:Y-m-d'],
            'jam_berangkat' => ['required', 'date_format:H:i'],
            'harga' => ['required', 'integer', 'min:1', 'max:2147483647'],
        ]);

        if (! $travel->isUpcoming((object) $input)) {
            throw ValidationException::withMessages(['tgl_berangkat' => 'Jadwal harus berangkat pada waktu yang akan datang.']);
        }

        DB::table('jadwal')->insert($input);

        return back()->with('status', 'Jadwal keberangkatan berhasil ditambahkan.');
    }

    public function manifest(int $schedule, TravelQueries $travel): View
    {
        $departure = $travel->schedules()->where('j.id_jadwal', $schedule)->first();
        abort_if(! $departure, 404);

        return view('pages.operations.manifest', [
            'schedule' => $departure,
            'passengers' => $travel->occupiedSeats()->where('pesan.id_jadwal', $schedule)
                ->leftJoin('member as m', 'm.id_member', '=', 'pesan.id_member')
                ->select('pesan.id_pesan', 'pesan.nomor_kursi', 'pesan.status', 'm.nama', 'm.telepon')
                ->orderBy('pesan.nomor_kursi')->get(),
        ]);
    }

    public function verification(Request $request, TravelQueries $travel): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['Dalam Proses', 'Lunas', 'Ditolak'])],
            'cari' => ['nullable', 'string', 'max:100'],
        ]);
        $status = $filters['status'] ?? 'Dalam Proses';
        $receipts = DB::table('konfirmasi_pesan as k')
            ->joinSub($travel->bookings(), 'pesanan', 'pesanan.id_pesan', '=', 'k.id_pesan')
            ->leftJoin('member as m', 'm.id_member', '=', 'pesanan.id_member')
            ->select('k.*', 'pesanan.status', 'pesanan.asal', 'pesanan.tujuan', 'pesanan.harga', 'pesanan.nomor_kursi', 'm.nama')
            ->where('pesanan.status', $status)
            ->when($filters['cari'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('k.no_resi', 'like', '%'.$search.'%')->orWhere('m.nama', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('k.tgl_transfer')->orderByDesc('k.jam_transfer')->orderBy('k.no_resi')
            ->paginate(12)->withQueryString();

        return view('pages.operations.verification', ['receipts' => $receipts, 'selectedStatus' => $status]);
    }

    public function decide(Request $request, int $booking): RedirectResponse
    {
        $input = $request->validate(['keputusan' => ['required', Rule::in(['Lunas', 'Ditolak'])]]);

        $updated = DB::table('pesan')->where('id_pesan', $booking)->where('status', 'Dalam Proses')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')->from('konfirmasi_pesan')->whereColumn('konfirmasi_pesan.id_pesan', 'pesan.id_pesan');
            })
            ->update(['status' => $input['keputusan']]);

        if ($updated === 0) {
            throw ValidationException::withMessages(['keputusan' => 'Konfirmasi ini sudah diproses atau tidak tersedia.']);
        }

        return back()->with('status', 'Status pembayaran berhasil diperbarui.');
    }
}
