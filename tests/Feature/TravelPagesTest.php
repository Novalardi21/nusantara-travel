<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TravelPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTravelTables();
    }

    public function test_payment_page_displays_confirmation_form_and_ticket_history(): void
    {
        $member = Member::factory()->create();
        $schedule = $this->createTravelSchedule();
        DB::table('pesan')->insert(['id_jadwal' => $schedule, 'id_member' => $member->id_member, 'nomor_kursi' => '1', 'status' => 'Belum Bayar']);

        $this->actingAs($member, 'member')->get(route('payment'))->assertOk()
            ->assertSee('Konfirmasi Pembayaran')
            ->assertSee('Nomor Rekening Pengirim')
            ->assertSee('Riwayat Reservasi &amp; Tiket Saya', false);
    }

    public function test_operations_dashboard_displays_fleet_and_customer_service_panels(): void
    {
        $this->actingAs(Admin::factory()->make(), 'admin')->get(route('operations.dashboard'))->assertOk()
            ->assertSee('Ringkasan Operasional &amp; Tiket', false)
            ->assertSee('Jadwal &amp; Armada', false)
            ->assertSee('Keluhan Masuk');
    }

    public function test_verification_page_displays_receipt_queue(): void
    {
        $this->actingAs(Admin::factory()->make(), 'admin')->get(route('operations.verification'))->assertOk()
            ->assertSee('Daftar Antrean Resi');
    }
}
