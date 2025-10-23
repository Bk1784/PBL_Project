<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
        public function test_admin_halaman_login_dapat_ditampilkan(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(status: 200);
    }

        public function test_admin_dapat_masuk_menggunakan_login_dengan_email_password(): void
    {
        $this->withoutMiddleware();

        $user = Admin::factory()->create([
            'password' => bcrypt('123'),
        ]);

        $response = $this->post( route('admin.login_submit'), [
            'email' => $user->email,
            'password' => '123',
        ]);

        $this->assertAuthenticatedAs($user, 'admin');
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_admin_tidak_dapat_masuk_dengan_kredensial_tidak_valid(): void
    {
        $user = Admin::factory()->create();

        $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
    public function test_admin_dapat_logout(): void
    {
        $this->withoutMiddleware();
        $user = Admin::factory()->create();

        $response = $this->actingAs($user)->get('/admin/logout');

         $this->assertGuest( 'admin');
        $response->assertRedirect(route('admin.login', absolute: false));
    }
}
