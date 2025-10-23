<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientRegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/client/register');

        $response->assertStatus(200);
    }

    public function test_client_dapat_melakukan_register(): void{
        $this->withoutMiddleware();

        $response = $this->post('/client/register/submit', [
            'name' => 'Test Client',
            'phone' => '082335845267',
            'address' => 'Jl. Test Address',
            'email' => 'tes@exampel.com',
            'password' => '123',
        ]);
        $this->assertGuest();
        $response->assertRedirect(route('client.login', absolute: false));
    }

    
}