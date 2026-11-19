<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use App\Models\OperatorLoket;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TravelWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createTravelTables();
    }

    public function test_member_can_book_and_staff_can_approve_a_recorded_transfer(): void
    {
        $member = Member::factory()->create();
        $schedule = $this->createTravelSchedule();

        $this->actingAs($member, 'member')->post(route('booking.store'), ['id_jadwal' => $schedule, 'nomor_kursi' => '1'])
            ->assertRedirect(route('payment', ['pesanan' => 1]));
        $this->assertDatabaseHas('pesan', ['id_pesan' => 1, 'id_member' => $member->id_member, 'status' => 'Belum Bayar']);
        $this->get(route('ticket', 1))->assertNotFound();

        $this->post(route('payment.store', 1), [
            'no_rek' => '001234567890',
            'tgl_transfer' => now('Asia/Jakarta')->toDateString(),
            'jam_transfer' => '08:00',
        ])->assertRedirect(route('payment', ['pesanan' => 1]));
        $this->assertDatabaseHas('konfirmasi_pesan', ['id_pesan' => 1, 'no_rek' => '001234567890']);
        $this->assertDatabaseHas('pesan', ['id_pesan' => 1, 'status' => 'Dalam Proses']);
        $receiptNumber = DB::table('konfirmasi_pesan')->where('id_pesan', 1)->value('no_resi');
        $this->assertMatchesRegularExpression('/^RES-[A-Z0-9]{16}$/D', $receiptNumber);
        $this->get(route('payment', ['pesanan' => 1]))->assertOk()->assertSee($receiptNumber);

        $this->actingAs(OperatorLoket::factory()->make(), 'operator')->get(route('operations.verification'))
            ->assertOk()->assertSee($receiptNumber);
        $this->patch(route('operations.verification.update', 1), ['keputusan' => 'Lunas'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('pesan', ['id_pesan' => 1, 'status' => 'Lunas']);
        $this->actingAs($member, 'member')->get(route('ticket', 1))->assertOk()->assertSee('E-Ticket #1');
    }

    public function test_stale_payment_token_reopens_the_form_without_saving_the_transfer(): void
    {
        $member = Member::factory()->create();
        $schedule = $this->createTravelSchedule();
        DB::table('pesan')->insert(['id_pesan' => 1, 'id_jadwal' => $schedule, 'id_member' => $member->id_member, 'nomor_kursi' => '1', 'status' => 'Belum Bayar']);
        $this->app->bind(ValidateCsrfToken::class, fn ($app) => new class($app, $app['encrypter']) extends ValidateCsrfToken
        {
            protected function runningUnitTests(): bool
            {
                return false;
            }
        });

        $transfer = [
            'no_rek' => '001234567890',
            'tgl_transfer' => now('Asia/Jakarta')->toDateString(),
            'jam_transfer' => '08:00',
        ];
        $paymentPage = route('payment', ['pesanan' => 1]);

        $this->actingAs($member, 'member')->withSession(['_token' => 'current-token'])
            ->post(route('payment.store', 1), [...$transfer, '_token' => 'stale-token'])
            ->assertStatus(303)->assertRedirect($paymentPage)->assertSessionHasErrors('payment');

        $this->assertDatabaseCount('konfirmasi_pesan', 0);
        $this->assertDatabaseHas('pesan', ['id_pesan' => 1, 'status' => 'Belum Bayar']);
        $this->get($paymentPage)->assertOk()->assertSee('Sesi formulir berakhir');
        $this->post(route('payment.store', 1), [...$transfer, '_token' => 'current-token'])->assertRedirect($paymentPage);
        $this->assertDatabaseHas('konfirmasi_pesan', ['id_pesan' => 1, 'no_rek' => '001234567890']);
    }

    public function test_another_member_cannot_claim_an_occupied_seat_or_read_the_booking(): void
    {
        $owner = Member::factory()->create();
        $other = Member::factory()->create();
        $schedule = $this->createTravelSchedule();
        DB::table('pesan')->insert(['id_pesan' => 1, 'id_jadwal' => $schedule, 'id_member' => $owner->id_member, 'nomor_kursi' => '1', 'status' => 'Lunas']);

        $this->actingAs($other, 'member')->post(route('booking.store'), ['id_jadwal' => $schedule, 'nomor_kursi' => '1'])
            ->assertSessionHasErrors('nomor_kursi');
        $this->assertDatabaseCount('pesan', 1);
        $this->get(route('payment', ['pesanan' => 1]))->assertNotFound();
        $this->get(route('ticket', 1))->assertNotFound();
        $this->post(route('payment.store', 1), [
            'no_rek' => '001234567890',
            'tgl_transfer' => now('Asia/Jakarta')->toDateString(),
            'jam_transfer' => '08:00',
        ])->assertNotFound();
        $this->assertDatabaseCount('konfirmasi_pesan', 0);
        $this->patch(route('operations.verification.update', 1), ['keputusan' => 'Ditolak'])->assertRedirect(route('login'));
    }

    public function test_member_can_update_profile_change_password_and_submit_a_complaint(): void
    {
        $member = Member::factory()->create(['password' => Hash::make('Sandi-awal-123')]);
        $this->actingAs($member, 'member')->get(route('profile'))->assertOk()->assertSee($member->username);

        $this->patch(route('profile.update'), [
            'nama' => 'Dina Anggraini',
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
            'jen_kelamin' => 'P',
        ])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('member', ['id_member' => $member->id_member, 'nama' => 'Dina Anggraini']);

        $password = 'Sandi-baru-123';
        $this->put(route('profile.password'), [
            'current_password' => 'sandi-salah',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('Sandi-awal-123', $member->fresh()->password));

        $this->put(route('profile.password'), [
            'current_password' => 'Sandi-awal-123',
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check($password, $member->fresh()->password));

        $this->post(route('complaints.store'), ['j_keluhan' => 'Armada', 'd_keluhan' => 'Mohon informasi mengenai bagasi.'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('keluhan', ['id_member' => $member->id_member, 'j_keluhan' => 'Armada']);
    }

    public function test_operator_can_view_manifest_but_cannot_manage_schedules_or_view_complaints(): void
    {
        $schedule = $this->createTravelSchedule();

        $this->actingAs(OperatorLoket::factory()->make(), 'operator')
            ->get(route('operations.dashboard'))->assertOk()
            ->assertDontSee('Tambah Jadwal')->assertDontSee('Keluhan Masuk')->assertDontSee('Total Keluhan');
        $this->get(route('operations.manifest', $schedule))->assertOk();
        $this->post(route('operations.schedules.store'), [])->assertForbidden();
        $this->assertDatabaseCount('jadwal', 1);
    }

    public function test_admin_can_add_a_future_schedule_that_appears_in_public_search(): void
    {
        $schedule = DB::table('jadwal')->where('id_jadwal', $this->createTravelSchedule())->first();

        $this->actingAs(Admin::factory()->make(), 'admin')->post(route('operations.schedules.store'), [
            'id_kota_asal' => $schedule->id_kota_asal,
            'id_kota_tujuan' => $schedule->id_kota_tujuan,
            'id_mobil' => $schedule->id_mobil,
            'tgl_berangkat' => now('Asia/Jakarta')->addDays(2)->toDateString(),
            'jam_berangkat' => '16:00',
            'harga' => 150000,
        ])->assertSessionHasNoErrors();

        $this->get(route('booking.schedules', ['asal' => $schedule->id_kota_asal, 'jadwal' => 2]))
            ->assertOk()->assertSee('150.000')->assertSee('Bandung')->assertSee('Jakarta');
    }
}
