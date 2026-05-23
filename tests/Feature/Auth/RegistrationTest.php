<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::registration());
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\WelcomeMail::class, function ($mail) {
            return $mail->hasTo('test@example.com') && $mail->user->name === 'John';
        });
    }
}
