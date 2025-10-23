<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientAuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/client/login');

        $response->assertStatus(200);
    }

    public function test_client_dapat_login_menggunakan_email_password(): void{
        $this->withoutMiddleware();

        $client = \App\Models\Client::factory()->create([
            'password' => bcrypt('123'),
        ]);

        $response = $this->post( route('client.login_submit'), [
            'email' => $client->email,
            'password' => '123',
        ]);

        $this->assertAuthenticatedAs($client, 'client');
        $response->assertRedirect(route('client.dashboard', absolute: false));
    }

    public function test_client_tidak_dapat_login_dengan_kredensial_tidak_valid(): void{
        $client = \App\Models\Client::factory()->create();

        $this->post('/client/login', [
            'email' => $client->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_client_dapat_logout(): void{
        $this->withoutMiddleware();
        $client = \App\Models\Client::factory()->create();

        $response = $this->actingAs($client)->get('/client/logout');

         $this->assertGuest( 'client');
        $response->assertRedirect(route('client.login', absolute: false));
    }
}
