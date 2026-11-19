<?php

namespace App\Http\Controllers;

use App\Services\TravelQueries;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TravelPaymentController extends Controller
{
    public function index(Request $request, TravelQueries $travel): View
    {
        $memberId = $request->user('member')->getAuthIdentifier();
        $bookings = $travel->bookings()->where('p.id_member', $memberId)->orderByDesc('p.id_pesan')->paginate(12)->withQueryString();
        $booking = $request->filled('pesanan')
            ? $travel->bookings()->where('p.id_member', $memberId)->where('p.id_pesan', $request->integer('pesanan'))->first()
            : $bookings->first();
        abort_if($request->filled('pesanan') && ! $booking, 404);

        return view('pages.member.payment', [
            'bookings' => $bookings,
            'booking' => $booking,
            'canConfirm' => $booking && $travel->isUpcoming($booking) && in_array($booking->status, ['Belum Bayar', 'Ditolak'], true),
            'receipts' => $booking ? DB::table('konfirmasi_pesan')->where('id_pesan', $booking->id_pesan)->orderByDesc('tgl_transfer')->orderByDesc('jam_transfer')->get() : collect(),
        ]);
    }

    public function store(Request $request, int $booking, TravelQueries $travel): RedirectResponse
    {
        $input = $request->validate([
            'no_rek' => ['required', 'string', 'regex:/^[0-9]{1,30}$/D'],
            'tgl_transfer' => ['required', 'date_format:Y-m-d', 'before_or_equal:'.now('Asia/Jakarta')->toDateString()],
            'jam_transfer' => ['required', 'date_format:H:i'],
        ]);

        try {
            $receiptNumber = DB::transaction(function () use ($request, $booking, $input, $travel): string {
                $order = DB::table('pesan')->where('id_pesan', $booking)
                    ->where('id_member', $request->user('member')->getAuthIdentifier())->lockForUpdate()->first();
                abort_if(! $order, 404);
                $schedule = DB::table('jadwal')->where('id_jadwal', $order->id_jadwal)->first();

                if (! $schedule || ! $travel->isUpcoming($schedule) || ! in_array($order->status, ['Belum Bayar', 'Ditolak'], true)) {
                    throw ValidationException::withMessages(['payment' => 'Pesanan ini tidak dapat menerima konfirmasi baru.']);
                }

                $receiptNumber = 'RES-'.Str::upper(Str::random(16));
                DB::table('konfirmasi_pesan')->insert([...$input, 'no_resi' => $receiptNumber, 'id_pesan' => $booking]);
                DB::table('pesan')->where('id_pesan', $booking)->update(['status' => 'Dalam Proses']);

                return $receiptNumber;
            }, 3);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['payment' => 'Nomor resi belum bisa dibuat. Silakan kirim konfirmasi lagi.']);
        }

        return redirect()->route('payment', ['pesanan' => $booking])
            ->with('status', "Konfirmasi transfer dikirim untuk diperiksa. Nomor resi internal: {$receiptNumber}.");
    }

    public function complaint(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'j_keluhan' => ['required', Rule::in(['Jadwal', 'Pembayaran', 'Armada', 'Layanan', 'Lainnya'])],
            'd_keluhan' => ['required', 'string', 'max:200'],
        ]);
        DB::table('keluhan')->insert([...$input, 'id_member' => $request->user('member')->getAuthIdentifier()]);

        return back()->with('status', 'Keluhan berhasil dikirim.');
    }

    public function ticket(Request $request, int $booking, TravelQueries $travel): View
    {
        $ticket = $travel->bookings()->where('p.id_pesan', $booking)
            ->where('p.id_member', $request->user('member')->getAuthIdentifier())
            ->where('p.status', 'Lunas')->first();
        abort_if(! $ticket, 404);

        return view('pages.member.ticket', ['ticket' => $ticket, 'member' => $request->user('member')]);
    }
}
