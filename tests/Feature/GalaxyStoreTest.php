<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Admin;
use App\Models\User;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\Rules\Email;
use Tests\TestCase;

class GalaxyStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_user_masuk_tanpa_login(): void
    {
        $response = $this->get('/dashboard' );
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    public function test_admin_untuk_update_profile(){
        $admin = Admin::factory()->create();

    $response = $this->actingAs($admin, 'admin')->put('/admin/profile/update', [
        'name' => 'Kurniawan Muhammad',
        'email' => 'admin@gmail.com',
        'address' => 'Jl. Merdeka No. 123, Jakarta',
        'phone' => '081234567890',
    ]);

    $this->assertDatabaseHas('admins', [
        'name' => 'Kurniawan Muhammad',
        'email' => 'admin@gmail.com',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/admin/profile');

}
}