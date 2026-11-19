<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table): void {
                $table->increments('id_admin');
                $table->string('email', 50)->unique();
                $table->string('password', 255);
                $table->string('no_telp', 20);
                $table->string('nama_lengkap', 100);
            });
        }

        if (! Schema::hasTable('operator_loket')) {
            Schema::create('operator_loket', function (Blueprint $table): void {
                $table->increments('id_opr');
                $table->string('user', 50)->unique();
                $table->string('password', 255);
                $table->string('nama', 100);
            });
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

        if (! Schema::hasTable('kota_asal')) {
            Schema::create('kota_asal', function (Blueprint $table): void {
                $table->increments('id_kota_asal');
                $table->string('nm_kota_asal', 27);
            });
        }

        if (! Schema::hasTable('kota_tujuan')) {
            Schema::create('kota_tujuan', function (Blueprint $table): void {
                $table->increments('id_kota_tujuan');
                $table->string('nm_kota_tujuan', 26);
            });
        }

        if (! Schema::hasTable('kendaraan')) {
            Schema::create('kendaraan', function (Blueprint $table): void {
                $table->increments('id_mobil');
                $table->string('jenis_mobil', 20);
                $table->string('nomor_polisi', 20);
                $table->string('warna_mobil', 20);
            });
        }

        if (! Schema::hasTable('jadwal')) {
            Schema::create('jadwal', function (Blueprint $table): void {
                $table->increments('id_jadwal');
                $table->integer('id_kota_asal')->index();
                $table->integer('id_kota_tujuan')->index();
                $table->integer('id_mobil')->index();
                $table->integer('harga');
                $table->date('tgl_berangkat')->index();
                $table->time('jam_berangkat');
            });
        }

        if (! Schema::hasTable('pesan')) {
            Schema::create('pesan', function (Blueprint $table): void {
                $table->increments('id_pesan');
                $table->enum('nomor_kursi', ['1', '2', '3', '4', '5']);
                $table->string('status', 100);
                $table->integer('id_jadwal')->index();
                $table->integer('id_member')->index();
            });
        }

        if (! Schema::hasTable('konfirmasi_pesan')) {
            Schema::create('konfirmasi_pesan', function (Blueprint $table): void {
                $table->string('no_resi', 20)->primary();
                $table->string('no_rek', 30);
                $table->date('tgl_transfer');
                $table->time('jam_transfer');
                $table->integer('id_pesan')->index();
            });
        }

        if (! Schema::hasTable('keluhan')) {
            Schema::create('keluhan', function (Blueprint $table): void {
                $table->increments('id_keluhan');
                $table->integer('id_member')->index();
                $table->string('j_keluhan', 20);
                $table->string('d_keluhan', 200);
            });
        }
    }

    public function down(): void
    {
        // Existing installations may own these tables, so rollback preserves their data.
    }
};
