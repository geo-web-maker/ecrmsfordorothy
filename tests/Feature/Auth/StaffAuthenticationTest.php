<?php

namespace Tests\Feature\Auth;

use App\Models\Stuff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_using_the_staff_login_screen(): void
    {
        $user = Stuff::factory()->admin()->create();

        $response = $this->post('/admin', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('officer.dashboard', absolute: false));
    }

    public function test_officer_can_authenticate_using_the_staff_login_screen(): void
    {
        $user = Stuff::factory()->officer()->create();

        $response = $this->post('/admin', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('officer.dashboard', absolute: false));
    }

    public function test_whistleblowers_cannot_authenticate_using_the_staff_login_screen(): void
    {
        $user = Stuff::factory()->whistleblower()->create();

        $this->post('/admin', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_staff_logout_redirects_to_splash_then_home(): void
    {
        $user = Stuff::factory()->admin()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('splash', absolute: false));
    }
}
