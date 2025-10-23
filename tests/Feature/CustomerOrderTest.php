<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_hanya_pesanan_yang_tidak_refunded_yang_ditampilkan(){
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();
        $this->actingAs($user);

        $order1 = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed'
        ]);

        OrderItem::factory()->create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
        ]);

        $order2 = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'refunded'
        ]);

        $response = $this->get(route('customer.orders.all_orders'));

        $response->assertStatus(200);
    }

    public function test_pengguna_dapat_download_invoice_pesanan(){
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'courier' => 'jne',
            'invoice_no' => 'INV12345678',
        ]);

        $product = Product::factory()->create([
            'price' => 50000,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 50000,
            'qty' => 2,
        ]);

        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('loadView')->once()->andReturnSelf();

        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('setPaper')->once()->with('A4')->andReturnSelf();

        \Barryvdh\DomPDF\Facade\Pdf::shouldReceive('download')->once()->with('invoice_'.$order->invoice_no.'.pdf')->andReturn(response('PDF Content', 200));

        $response = $this->get(route('customer.orders.invoice', $order->id));

        $response->assertStatus(200);
        $response->assertSee('PDF Content');
    }

    public function test_pengguna_tidak_dapat_download_invoice_pesanan_orang_lain(){
        $this->withoutMiddleware();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user2->id,
            'courier' => 'jne',
        ]);

        $this->actingAs($user1);

        $response = $this->get(route('customer.orders.invoice', $order->id));

        $response->assertStatus(404);
    }

    public function test_pengguna_dapat_melakukan_cash_order_degan_berhasil(){
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        //membuat produk
        $product = Product::factory()->create([
            'name' => 'Produk Test',
            'price' => 10000,
            'qty' => 100,
        ]);

        //isi keranjang di session
        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 2,
            ]
        ];

        //simpan ke session
        session(['cart' => $cart]);

        //kirim request post ke COD
        $response = $this->post(route('customer.orders.cash_order'), [
            'courier_selected' => 'JNE Reguler',
        ]);

        //pastikan respon sukses
        $response->assertStatus(200);
        $response->assertViewIs('customer.checkout.thanks');

        //pastikan order tersimpan di database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'COD',
            'courier' => 'JNE REGULER',
            'status' => 'pending',
        ]);

        //pastikan order item tersimpan di database
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'qty' => 2,
        ]);

        //pastikan stok produk berkurang
        $this->assertEquals(98, $product->fresh()->qty);  
    }

    public function test_order_gagal_jika_keranjang_kosong(){
        $this->withoutMiddleware();

        //membuat user
        $user = User::factory()->create();
        $this->actingAs($user);

        //memastikan keranjang kosong
        session(['cart' => []]);

        //kirim request ke post COD
        $response = $this->post(route('customer.orders.cash_order'), [
            'courier_selected' => 'JNE Reguler',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Order gagal: Keranjang belanja kosong');

    }

    public function test_order_gagal_jika_stok_tidak_mencukupi(){
        $this->withoutMiddleware();

        //membuat user
        $user = User::factory()->create();
        $this->actingAs($user);

        //membuat produk
        $product = Product::factory()->create([
            'name' => 'Produk Test',
            'price' => 10000,
            'qty' => 1,
        ]);

        //beli lebih banyak produk daripada stok yang di atas
        $cart = [
            $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 5,
            ]
            ];

            //simpan ke session
            session(['cart' => $cart]);

            //kirim request post ke COD
            $response = $this->post(route('customer.orders.cash_order'), [
                'courier_selected' => 'JNE Reguler',
            ]);

            $response->assertRedirect();
            $response->assertSessionHas('message', 'Order gagal: Stok Produk Test tidak mencukupi');
    }

}
