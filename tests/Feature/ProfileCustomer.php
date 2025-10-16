<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileCustomer extends TestCase
{
    use RefreshDatabase;

    
    public function test_akses_profil_customer_memerlukan_autentikasi(): void
    {
        $response = $this->get('/customer/profile');

        $response->assertRedirect('/login');
    }

    
    public function test_customer_terautentikasi_dapat_akses_profil(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web');
        $this->actingAs($user, 'customer');

        $response = $this->get('/customer/profile');

        $response->assertStatus(200);
        $response->assertViewIs('customer.profile');
        $response->assertViewHas('customer');
    }

    
    public function test_customer_terautentikasi_dapat_akses_edit_profil(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web');
        $this->actingAs($user, 'customer');

        $response = $this->get('/customer/edit/profile');

        $response->assertStatus(200);
        $response->assertViewIs('customer.edit_profile');
        $response->assertViewHas('customer');
    }

    
    public function test_customer_dapat_memperbarui_profil_dengan_data_valid(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'phone' => '123456789',
            'address' => 'Old Address',
        ]);

        $this->actingAs($user, 'web');
        $this->actingAs($user, 'customer');

        $updateData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '987654321',
            'address' => 'New Address',
        ];

        $response = $this->withoutMiddleware()->post('/profile/store', $updateData);

        $response->assertRedirect('/customer/profile');
        $response->assertSessionHas('message', 'Profile Updated Successfully');
        $response->assertSessionHas('alert-type', 'success');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('987654321', $user->phone);
        $this->assertEquals('New Address', $user->address);
    }
    

    public function test_customer_terautentikasi_dapat_akses_ubah_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web');
        $this->actingAs($user, 'customer');

        $response = $this->get('/change-password');

        $response->assertStatus(200);
        $response->assertViewIs('customer.change_password');
    }

    
    public function test_customer_dapat_mengubah_password_dengan_data_valid(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $this->actingAs($user, 'web');
        $this->actingAs($user, 'customer');

        $updateData = [
            'old_password' => 'oldpassword',
            'new_password' => 'newpassword123',
            'new_password_confirmation' => 'newpassword123',
        ];

        $response = $this->withoutMiddleware()->post('/update-password', $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Password berhasil diubah.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }




    public function test_customer_tidak_dapat_memperbarui_profil_dengan_data_tidak_valid(): void
{
    $user = User::factory()->create([
        'role' => 'customer',
    ]);

    $invalidData = [
        'name' => '',            // nama kosong
        'email' => 'bukanemail', // email tidak valid
        'phone' => '',           // no hp kosong
        'address' => '',         // alamat kosong
    ];

    $this->actingAs($user, 'web');
    $this->actingAs($user, 'customer');

    // Kirim request POST TANPA menonaktifkan middleware apapun
    $response = $this
        ->from('/customer/edit/profile')
        ->post('/profile/store', $invalidData);

    $response->assertStatus(302);
    $response->assertRedirect('/customer/profile');
    $response->assertSessionHasErrors(['name', 'email', 'phone', 'address']);

    $user->refresh();

    $this->assertNotEquals($user->name, $invalidData['name']);
    $this->assertNotEquals($user->email, $invalidData['email']);
    $this->assertNotEquals($user->phone, $invalidData['phone']);
    $this->assertNotEquals($user->address, $invalidData['address']);
}



}
