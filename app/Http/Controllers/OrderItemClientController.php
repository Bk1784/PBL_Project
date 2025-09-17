<?php

namespace App\Http\Controllers;

use App\Models\OrderItemClient;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderItemClientController extends Controller
{
    public function Halaman(){
        $products = Product::latest()->get();

        return view('client.Order.penjualan_offline', compact( 'products'));
    }
public function KirimPembelianOffline(Request $request)
{
    $request->validate([
        'cart_data' => 'required'  // hanya validasi cart_data
    ]);

    $cart = json_decode($request->cart_data, true);

    if (!$cart || count($cart) === 0) {
        return back()->with('error', 'Keranjang kosong!');
    }

    foreach ($cart as $item) {
        $product = Product::findOrFail($item['id']);

        // cek stok
        if ($product->qty < $item['qty']) {
            return back()->with('error', "Stok {$product->name} tidak mencukupi!");
        }

        // hitung total harga untuk item ini
        $totalPrice = $product->price * $item['qty'];

        // simpan ke order_item_client
        OrderItemClient::create([
            'product_id' => $product->id,
            'qty' => $item['qty'],
            'price' => $totalPrice
        ]);

        // kurangi stok produk
        $product->decrement('qty', $item['qty']);
    }

    return back()->with('success', 'Transaksi berhasil dicatat!');
}
}
