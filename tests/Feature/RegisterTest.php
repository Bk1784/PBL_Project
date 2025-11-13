<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{


    public function test_user_dapat_register_dengan_data_valid(): void
{
    Event::fake();

    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/register', [
            'name' => 'Bagus',
            'email' => 'bagus@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    // sesuaikan redirect-nya dengan route kamu (login atau dashboard customer)
    $response->assertRedirect(route('customer.login'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'bagus@example.com',
    ]);

    Event::assertDispatched(Registered::class);
}

public function test_register_gagal_jika_data_tidak_valid(): void
{
    $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/register', [
            'name' => '',
            'email' => 'bukan-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertGuest();
}

public function test_password_disimpan_dalam_bentuk_hash(): void
{
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->post('/customer/register', [
            'name' => 'Test Hash',
            'email' => 'hash@example.com',
            'password' => 'mypassword',
            'password_confirmation' => 'mypassword',
        ]);

    $user = User::where('email', 'hash@example.com')->first();

    $this->assertNotNull($user, 'User tidak tersimpan di database');
    $this->assertTrue(Hash::check('mypassword', $user->password));
}


}
