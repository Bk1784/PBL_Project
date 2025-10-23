<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerAddToCartTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_pengguna_dapat_melihat_produk() {

        $this->withoutExceptionHandling();

        $customer = \App\Models\User::factory()->create();

        $this->actingAs($customer);
        $response = $this->get('/atk_dashboard');

        $response->assertStatus(200);
    }

    public function test_halaman_detail_produk_dapat_diakses(): void
    {
        $this->withoutMiddleware();

        $product = \App\Models\Product::factory()->create([
            'name' => 'Produk test',
            'price' => 15000,
            'image' => 'produk_test.jpg'
        ]);

        $response = $this->get((route('product.details', $product->id)));
        $response->assertStatus(200);
    }
    
    public function test_pengguna_dapat_menambahkan_produk_ke_keranjang(): void
    {
        $this->withoutMiddleware();

        $product = \App\Models\Product::factory()->create([
            'name' => 'Produk Test',
            'price' => 10000,
            'image' => 'produk_test.jpg',
        ]);

        $response = $this->get(route('add_to_cart', $product->id));

        $response->assertStatus(302);

        $cart = $response->getSession()->get('cart');

        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]['qty']);
        $this->assertEquals($product->name, $cart[$product->id]['name']);
        $this->assertEquals($product->price, $cart[$product->id]['price']);

        $response->assertSessionHas('message', 'Add to Cart Successfully');
        $response->assertSessionHas('alert-type', 'success');
    }

    public function test_pengguna_dapat_memperbarui_kuantitas_produk_di_keranjang(): void {
        $this->withoutMiddleware();

        $cart = [ //ini merupakan data keranjang awal
            1 => [ 
                'id' => 1,
                'name' => 'Produk Test',
                'price' => 10000,
                'qty' => 1
            ],
        ];

        $this->withSession(['cart' => $cart]);

        $response = $this->postJson('/cart/update-quantity', [
            'id' => 1, // mengambil data dari produk_id dan quntitynya 2
            'quantity' => 2
        ]);

        $response->assertStatus(200) //response harus 200 berarti berhasil
                 ->assertJson([ // artinya data yang diambil outputnya harus sesuai seperti ini
                     'success' => true,
                     'message' => 'Cart updated successfully',
                     'totalItems' => 2,
                     'grandTotal' => 20000,
                 ]);
        $this->assertEquals(2, session('cart')[1]['qty']); //verifikasi di session cart
    }

    public function test_pengguna_tidak_dapat_memperbarui_kuantitas_ke_nol(){
        $this->withoutMiddleware();

        $cart = [
            1 => [ 
                'id' => 1,
                'name' => 'Produk Test',
                'price' => 10000,
                'qty' => 1
            ],
        ];

        $this->withSession(['cart' => $cart]);

        $response = $this->postJson( '/cart/update-quantity', [
            'id' => 1,
            'quantity' => 0
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['quantity'])->assertJsonFragment([
            'quantity' => ['The quantity field must be at least 1.'],
        ]);

        $this->assertEquals(1, session('cart')[1]['qty']);
    }


    public function test_pengguna_dapat_menghapus_item_dari_keranjang(){
        $this->withoutMiddleware();

        $product = Product::factory()->create([
            'name' => 'Produk Test',
            'price' => 20000,
            'image' => 'produk_test.jpg',
        ]);

        $cart = [ //ini merupakan data keranjang awal
            $product->id => [ 
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'qty' => 2
            ],
        ];

        session(['cart' => $cart]);

        $response = $this->postJson(route('cart.remove'), [
            'id' => $product->id,
        ]);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
        ]);

        $updatedCart = session('cart');
        $this->assertArrayNotHasKey($product->id, $updatedCart ?? []);
    }

    public function test_pengguna_melakukan_checkout_dari_keranjang(){
        $this->withoutMiddleware();

        //membuat data produk
       $product = Product::factory()->create([
            'name' => 'Produk Test',
            'price' => 20000,
            'image' => 'produk_test.jpg',
       ]);
        //menyiapkan data dikeranjang
       $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'qty' => $product->id,
            ]];
        //
        
        //membuat sesi
        $this->withSession(['cart' => $cart]);

        //membuat response
        $response = $this->get(route('customer.checkout.view_checkout'));
        $response->assertStatus(302);
        
}
}