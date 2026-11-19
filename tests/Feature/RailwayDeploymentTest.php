<?php

namespace Tests\Feature;

use Database\Seeders\RailwaySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RailwayDeploymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['railway_seed' => [
            'admin_email' => 'admin@example.test',
            'admin_password' => 'Admin-test-2026',
            'operator_username' => 'operator@example.test',
            'operator_password' => 'Operator-test-2026',
            'demo_data' => false,
        ]]);
    }

    public function test_fresh_database_can_be_seeded_without_demo_members(): void
    {
        $this->freezeTime();
        $this->seed(RailwaySeeder::class);

        $this->assertDatabaseCount('admin', 1);
        $this->assertDatabaseCount('operator_loket', 1);
        $this->assertDatabaseCount('kota_asal', 6);
        $this->assertDatabaseCount('kota_tujuan', 6);
        $this->assertDatabaseCount('kendaraan', 3);
        $this->assertDatabaseCount('jadwal', 12);
        $this->assertDatabaseCount('member', 0);
        $this->assertDatabaseCount('pesan', 0);
        $this->assertDatabaseCount('konfirmasi_pesan', 0);
        $this->assertDatabaseCount('keluhan', 0);
        $this->assertSame(0, DB::table('jadwal')->where('tgl_berangkat', '<=', now('Asia/Jakarta')->toDateString())->count());

        $this->post(route('login.store'), [
            'identifier' => 'admin@example.test',
            'password' => 'Admin-test-2026',
        ])->assertRedirect(route('operations.dashboard'));
        $this->get(route('operations.dashboard'))->assertOk();
    }

    public function test_demo_data_populates_related_business_tables_and_can_be_repeated_without_resetting_accounts(): void
    {
        $this->freezeTime();
        config(['railway_seed.demo_data' => true]);
        $this->seed(RailwaySeeder::class);
        $passwordHash = Hash::make('Password-diubah-2026');
        DB::table('admin')->where('email', 'admin@example.test')->update(['password' => $passwordHash]);
        $this->seed(RailwaySeeder::class);

        $this->assertDatabaseCount('admin', 1);
        $this->assertDatabaseCount('operator_loket', 1);
        $this->assertDatabaseCount('kota_asal', 6);
        $this->assertDatabaseCount('kota_tujuan', 6);
        $this->assertDatabaseCount('kendaraan', 3);
        $this->assertDatabaseCount('jadwal', 12);
        $this->assertDatabaseCount('member', 1);
        $this->assertDatabaseCount('pesan', 3);
        $this->assertDatabaseCount('konfirmasi_pesan', 2);
        $this->assertDatabaseCount('keluhan', 1);
        $this->assertSame($passwordHash, DB::table('admin')->value('password'));
        $this->assertSame(3, DB::table('pesan')->join('member', 'member.id_member', '=', 'pesan.id_member')
            ->join('jadwal', 'jadwal.id_jadwal', '=', 'pesan.id_jadwal')->count());
        $this->assertDatabaseHas('pesan', ['status' => 'Lunas']);
        $this->assertDatabaseHas('pesan', ['status' => 'Dalam Proses']);
        $this->assertDatabaseHas('pesan', ['status' => 'Belum Bayar']);

        $this->post(route('login.store'), [
            'identifier' => 'operator@example.test',
            'password' => 'Operator-test-2026',
        ])->assertRedirect(route('operations.dashboard'));
        $this->get(route('operations.dashboard'))->assertOk();
        $this->get(route('operations.verification'))->assertOk();
    }

    public function test_seeding_requires_an_explicit_password(): void
    {
        config(['railway_seed.admin_password' => null]);
        $this->expectException(ValidationException::class);
        $this->seed(RailwaySeeder::class);
    }

    public function test_migration_preserves_existing_business_data(): void
    {
        DB::table('kendaraan')->insert([
            'jenis_mobil' => 'HiAce', 'nomor_polisi' => 'D 1234 AB', 'warna_mobil' => 'Hitam',
        ]);
        $migration = require database_path('migrations/2026_11_19_004048_create_missing_travel_tables.php');
        $migration->up();
        $migration->down();

        $this->assertDatabaseCount('kendaraan', 1);
        $this->assertDatabaseHas('kendaraan', ['nomor_polisi' => 'D 1234 AB', 'warna_mobil' => 'Hitam']);
    }
}
