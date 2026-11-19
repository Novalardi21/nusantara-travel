<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TravelQueries
{
    public function schedules(): Builder
    {
        return DB::table('jadwal as j')
            ->leftJoin('kota_asal as a', 'a.id_kota_asal', '=', 'j.id_kota_asal')
            ->leftJoin('kota_tujuan as t', 't.id_kota_tujuan', '=', 'j.id_kota_tujuan')
            ->leftJoin('kendaraan as k', 'k.id_mobil', '=', 'j.id_mobil')
            ->select('j.*', 'a.nm_kota_asal as asal', 't.nm_kota_tujuan as tujuan', 'k.jenis_mobil', 'k.nomor_polisi');
    }

    public function bookings(): Builder
    {
        return DB::table('pesan as p')
            ->leftJoinSub($this->schedules(), 'jadwal', 'jadwal.id_jadwal', '=', 'p.id_jadwal')
            ->select('p.*', 'jadwal.asal', 'jadwal.tujuan', 'jadwal.harga', 'jadwal.tgl_berangkat', 'jadwal.jam_berangkat', 'jadwal.jenis_mobil', 'jadwal.nomor_polisi');
    }

    public function occupiedSeats(): Builder
    {
        return DB::table('pesan')->where('status', '!=', 'Dibatalkan');
    }

    public function isUpcoming(object $schedule): bool
    {
        return $schedule->tgl_berangkat !== null
            && CarbonImmutable::parse($schedule->tgl_berangkat.' '.$schedule->jam_berangkat, 'Asia/Jakarta')->isFuture();
    }
}
