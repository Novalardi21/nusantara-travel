<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function createTravelTables(): void
    {
        if (Schema::hasTable('jadwal')) {
            return;
        }

        if (! Schema::hasTable('member')) {
            Schema::create('member', function (Blueprint $table): void {
                $table->increments('id_member');
                $table->string('nama', 100);
                $table->string('alamat', 100);
                $table->string('telepon', 20);
                $table->enum('jen_kelamin', ['L', 'P']);
                $table->string('username', 20)->unique();
                $table->string('password', 255);
            });
        }

        Schema::create('kota_asal', function (Blueprint $table): void {
            $table->increments('id_kota_asal');
            $table->string('nm_kota_asal', 27);
        });
        Schema::create('kota_tujuan', function (Blueprint $table): void {
            $table->increments('id_kota_tujuan');
            $table->string('nm_kota_tujuan', 26);
        });
        Schema::create('kendaraan', function (Blueprint $table): void {
            $table->increments('id_mobil');
            $table->string('jenis_mobil', 20);
            $table->string('nomor_polisi', 20);
            $table->string('warna_mobil', 20);
        });
        Schema::create('jadwal', function (Blueprint $table): void {
            $table->increments('id_jadwal');
            $table->integer('id_kota_asal');
            $table->integer('id_kota_tujuan');
            $table->integer('id_mobil');
            $table->integer('harga');
            $table->date('tgl_berangkat');
            $table->time('jam_berangkat');
        });
        Schema::create('pesan', function (Blueprint $table): void {
            $table->increments('id_pesan');
            $table->integer('id_jadwal');
            $table->integer('id_member');
            $table->enum('nomor_kursi', ['1', '2', '3', '4', '5']);
            $table->string('status', 100);
        });
        Schema::create('konfirmasi_pesan', function (Blueprint $table): void {
            $table->string('no_resi', 20)->primary();
            $table->string('no_rek', 30);
            $table->date('tgl_transfer');
            $table->time('jam_transfer');
            $table->integer('id_pesan');
        });
        Schema::create('keluhan', function (Blueprint $table): void {
            $table->increments('id_keluhan');
            $table->integer('id_member');
            $table->string('j_keluhan', 20);
            $table->string('d_keluhan', 200);
        });
    }

    protected function createTravelSchedule(): int
    {
        $origin = DB::table('kota_asal')->insertGetId(['nm_kota_asal' => 'Bandung'], 'id_kota_asal');
        $destination = DB::table('kota_tujuan')->insertGetId(['nm_kota_tujuan' => 'Jakarta'], 'id_kota_tujuan');
        $vehicle = DB::table('kendaraan')->insertGetId(['jenis_mobil' => 'HiAce', 'nomor_polisi' => 'D 1000 AAA', 'warna_mobil' => 'Putih'], 'id_mobil');

        return DB::table('jadwal')->insertGetId([
            'id_kota_asal' => $origin,
            'id_kota_tujuan' => $destination,
            'id_mobil' => $vehicle,
            'harga' => 120000,
            'tgl_berangkat' => now('Asia/Jakarta')->addDay()->toDateString(),
            'jam_berangkat' => '09:00:00',
        ], 'id_jadwal');
    }
}
