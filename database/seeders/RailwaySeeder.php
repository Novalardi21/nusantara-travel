<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RailwaySeeder extends Seeder
{
    public function run(): void
    {
        $settings = Validator::make(config('railway_seed'), [
            'admin_email' => ['required', 'email', 'max:50'],
            'admin_password' => ['required', 'string', 'min:12', 'max:72'],
            'operator_username' => ['required', 'string', 'max:50', 'different:admin_email'],
            'operator_password' => ['required', 'string', 'min:12', 'max:72'],
            'demo_data' => ['boolean'],
        ])->validate();

        DB::transaction(function () use ($settings): void {
            $this->seedAccounts($settings);
            $this->seedTravelData($settings['demo_data']);
        });
    }

    /** @param array{admin_email: string, admin_password: string, operator_username: string, operator_password: string, demo_data: bool} $settings */
    private function seedAccounts(array $settings): void
    {
        if (! DB::table('admin')->where('email', $settings['admin_email'])->exists()) {
            DB::table('admin')->insert([
                'email' => $settings['admin_email'],
                'password' => Hash::make($settings['admin_password']),
                'no_telp' => '',
                'nama_lengkap' => 'Administrator Nusantara',
            ]);
        }

        if (! DB::table('operator_loket')->where('user', $settings['operator_username'])->exists()) {
            DB::table('operator_loket')->insert([
                'user' => $settings['operator_username'],
                'password' => Hash::make($settings['operator_password']),
                'nama' => 'Operator Loket Nusantara',
            ]);
        }
    }

    private function seedTravelData(bool $demo): void
    {
        $cities = ['Jakarta', 'Bandung', 'Bogor', 'Cirebon', 'Semarang', 'Yogyakarta'];
        $origins = [];
        $destinations = [];
        foreach ($cities as $city) {
            $origins[$city] = $this->recordId('kota_asal', 'id_kota_asal', ['nm_kota_asal' => $city]);
            $destinations[$city] = $this->recordId('kota_tujuan', 'id_kota_tujuan', ['nm_kota_tujuan' => $city]);
        }

        $vehicles = [];
        foreach (['Toyota HiAce', 'Innova Zenix', 'Isuzu Elf'] as $index => $type) {
            $vehicles[] = $this->recordId('kendaraan', 'id_mobil', ['nomor_polisi' => 'DEMO-00'.($index + 1)], [
                'jenis_mobil' => $type,
                'warna_mobil' => 'Putih',
            ]);
        }

        $routes = [
            ['Jakarta', 'Bandung', 150000],
            ['Bandung', 'Jakarta', 150000],
            ['Bogor', 'Bandung', 130000],
            ['Bandung', 'Cirebon', 140000],
            ['Cirebon', 'Semarang', 160000],
            ['Semarang', 'Yogyakarta', 120000],
        ];
        $schedules = [];
        foreach ([1, 2] as $day) {
            foreach ($routes as $index => [$origin, $destination, $price]) {
                $schedules[] = $this->recordId('jadwal', 'id_jadwal', [
                    'id_kota_asal' => $origins[$origin],
                    'id_kota_tujuan' => $destinations[$destination],
                    'id_mobil' => $vehicles[$index % count($vehicles)],
                    'tgl_berangkat' => now('Asia/Jakarta')->addDays($day)->toDateString(),
                    'jam_berangkat' => $index < 3 ? '09:00:00' : '15:00:00',
                ], ['harga' => $price]);
            }
        }

        if ($demo) {
            $this->seedDemoTransactions($schedules);
        }
    }

    /** @param list<int> $schedules */
    private function seedDemoTransactions(array $schedules): void
    {
        $memberId = $this->recordId('member', 'id_member', ['username' => 'demo_member'], [
            'nama' => 'Penumpang Demo',
            'alamat' => 'Data contoh untuk demonstrasi',
            'telepon' => '080000000000',
            'jen_kelamin' => 'L',
            'password' => Hash::make(Str::random(40)),
        ]);

        $sampleReceipts = [
            ['DEMO-LUNAS-001', 'Lunas'],
            ['DEMO-PROSES-001', 'Dalam Proses'],
            ['DEMO-BELUM-001', 'Belum Bayar'],
        ];
        foreach ($sampleReceipts as $index => [$reference, $status]) {
            if (DB::table('konfirmasi_pesan')->where('no_resi', $reference)->exists()) {
                continue;
            }

            $scheduleId = $schedules[$index];
            if (DB::table('pesan')->where('id_member', $memberId)->where('id_jadwal', $scheduleId)->exists()) {
                continue;
            }

            $occupied = DB::table('pesan')->where('id_jadwal', $scheduleId)->where('status', '!=', 'Dibatalkan')
                ->pluck('nomor_kursi')->map(fn ($seat): string => (string) $seat)->all();
            $seat = collect(['1', '2', '3', '4', '5'])->first(fn (string $seat): bool => ! in_array($seat, $occupied, true));
            if ($seat === null) {
                continue;
            }

            $bookingId = DB::table('pesan')->insertGetId([
                'id_jadwal' => $scheduleId,
                'id_member' => $memberId,
                'nomor_kursi' => $seat,
                'status' => $status,
            ], 'id_pesan');

            if ($status !== 'Belum Bayar') {
                DB::table('konfirmasi_pesan')->insert([
                    'no_resi' => $reference,
                    'no_rek' => '000000000001',
                    'tgl_transfer' => now('Asia/Jakarta')->toDateString(),
                    'jam_transfer' => '08:00:00',
                    'id_pesan' => $bookingId,
                ]);
            }
        }

        $this->recordId('keluhan', 'id_keluhan', [
            'id_member' => $memberId,
            'j_keluhan' => 'Layanan',
            'd_keluhan' => 'Contoh keluhan: mohon informasi titik keberangkatan dan ketentuan bagasi.',
        ]);
    }

    /**
     * @param  array<string, int|string>  $identity
     * @param  array<string, int|string>  $attributes
     */
    private function recordId(string $table, string $key, array $identity, array $attributes = []): int
    {
        $existingId = DB::table($table)->where($identity)->value($key);

        return $existingId !== null
            ? (int) $existingId
            : (int) DB::table($table)->insertGetId([...$identity, ...$attributes], $key);
    }
}
