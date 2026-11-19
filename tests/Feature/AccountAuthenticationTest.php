<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use App\Models\OperatorLoket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_registration_pages_render_with_a_single_login_form(): void
    {
        $this->get(route('login'))->assertOk()
            ->assertSee('Username atau Email')
            ->assertDontSee('Pilih Jenis Akun');

        $this->get(route('register'))->assertOk()
            ->assertSee('Daftar Akun Member Baru');
    }

    public function test_registration_creates_a_member_with_a_hashed_password(): void
    {
        $member = Member::factory()->make(['username' => 'member_baru']);
        $password = 'Contoh-sandi-2026';

        $this->post(route('register.store'), [
            ...$member->only(['nama', 'alamat', 'telepon', 'jen_kelamin', 'username']),
            'password' => $password,
            'password_confirmation' => $password,
        ])->assertRedirect(route('booking'));

        $registered = Member::where('username', 'member_baru')->firstOrFail();
        $this->assertTrue(Hash::check($password, $registered->password));
        $this->assertAuthenticatedAs($registered, 'member');
    }

    public function test_legacy_member_can_sign_in_and_password_is_upgraded(): void
    {
        $member = Member::factory()->create(['password' => 'lama123']);

        $this->post(route('login.store'), ['identifier' => $member->username, 'password' => 'lama123'])
            ->assertRedirect(route('booking'));

        $this->assertAuthenticatedAs($member, 'member');
        $this->assertTrue(Hash::check('lama123', $member->fresh()->password));
        $this->get(route('operations.dashboard'))->assertRedirect(route('login'));
    }

    public function test_admin_signs_in_with_email_and_can_access_operations(): void
    {
        $admin = Admin::factory()->create(['password' => Hash::make('admin-test-123')]);

        $this->post(route('login.store'), ['identifier' => $admin->email, 'password' => 'admin-test-123'])
            ->assertRedirect(route('operations.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');
        $this->createTravelTables();
        $this->get(route('operations.dashboard'))->assertOk();
    }

    public function test_operator_signs_in_with_username_and_can_sign_out(): void
    {
        $operator = OperatorLoket::factory()->create(['password' => Hash::make('operator-test-123')]);

        $this->post(route('login.store'), ['identifier' => $operator->user, 'password' => 'operator-test-123'])
            ->assertRedirect(route('operations.dashboard'));

        $this->assertAuthenticatedAs($operator, 'operator');
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest('operator');
    }

    public function test_invalid_credentials_do_not_authenticate(): void
    {
        $member = Member::factory()->create();

        $this->from(route('login'))->post(route('login.store'), [
            'identifier' => $member->username,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))->assertSessionHasErrors('identifier');

        $this->assertGuest('member');
    }

    public function test_registration_rejects_an_existing_username(): void
    {
        $member = Member::factory()->create();

        $this->from(route('register'))->post(route('register.store'), [
            ...$member->only(['nama', 'alamat', 'telepon', 'jen_kelamin', 'username']),
            'password' => 'Contoh-sandi-2026',
            'password_confirmation' => 'Contoh-sandi-2026',
        ])->assertRedirect(route('register'))->assertSessionHasErrors('username');

        $this->assertDatabaseCount('member', 1);
    }

    public function test_guests_cannot_open_the_operations_pages(): void
    {
        $this->get(route('operations.dashboard'))->assertRedirect(route('login'));
        $this->get(route('operations.verification'))->assertRedirect(route('login'));
    }
}
