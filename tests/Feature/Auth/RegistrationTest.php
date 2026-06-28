<?php

namespace Tests\Feature\Auth;

use App\Mail\WelcomeUser;
use App\Models\Stuff;
use App\Models\Whistleblower;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Jane Nakato',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('stuff', [
            'email' => 'test@example.com',
            'role' => 'Whistleblower',
        ]);

        $stuff = Stuff::where('email', 'test@example.com')->first();

        $this->assertDatabaseHas('whistleblower', [
            'stuff_id' => $stuff->stuff_id,
            'first_name' => 'Jane',
            'last_name' => 'Nakato',
        ]);

        $this->assertInstanceOf(Whistleblower::class, $stuff->whistleblowerProfile);

        Mail::assertSent(WelcomeUser::class, function (WelcomeUser $mail) use ($stuff) {
            return $mail->hasTo('test@example.com')
                && $mail->user->is($stuff);
        });

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
    }
}
