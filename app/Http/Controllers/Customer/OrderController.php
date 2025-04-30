<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('customer.orders.all_orders', compact('orders'));
        
    }

    public function OrderDetails($id)
    {
        $order = Order::with('orderItems')->where('user_id', Auth::id())->findOrFail($id);
        return view('customer.orders.order_details', compact('order'));
    }

    public function CancelOrder($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (in_array($order->status, ['pending', 'confirmed'])) {
            $order->status = 'cancelled';
            $order->save();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan.');
    }

    public function ConfirmReceived($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status == 'delivered') {
            $order->status = 'received';
            $order->save();
            return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi.');
        }

        return redirect()->back()->with('error', 'Pesanan belum bisa dikonfirmasi.');
    }

    public function downloadInvoice($order_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        $orderItem = OrderItem::with(['product', 'order.user'])->where('order_id', $order_id)->get();
       
        
        $shippingCosts = [
            'JNE Reguler' => 15000,
            'J&T Express' => 17000,
            'SiCepat' => 13000,
            'POS Indonesia' => 14000,
        ];
    
        // Ambil nama kurir dan cari biaya kirim
       
        $courierName = $order->courier;
        $shippingFee = $shippingCosts[$courierName] ?? 15000;

        $Price = $orderItem->sum(fn($item) => $item->price * $item->qty);

        $totalAmount = $Price + $shippingFee;

        $pdf = PDF::loadView('order.invoice_download', compact('order', 'orderItem', 'totalAmount', 'Price', 'shippingFee'))
                  ->setPaper('A4');

        return $pdf->download('invoice_' . $order->invoice_no . '.pdf');
    }


    public function CashOrder(Request $request)
    {
        DB::beginTransaction();
    
        try {
            $cartItems = session()->get('cart', []);
            $totalAmount = 0;
    
            // 1. Validasi keranjang tidak kosong
            if (empty($cartItems)) {
                throw new \Exception('Keranjang belanja kosong');
            }
    
            // 2. Hitung total amount dan validasi stok
            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                
                if (!$product) {
                    throw new \Exception("Produk ID {$item['id']} tidak ditemukan");
                }
                
                if ($product->qty < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi");
                }
                
                $totalAmount += ($item['price'] * $item['qty']);
            }
    
            // 3. Hitung ongkir
            $shippingFee = [
                'JNE Reguler' => 15000,
                'J&T Express' => 17000,
                'SiCepat' => 13000,
                'POS Indonesia' => 14000,
            ][$request->courier_selected] ?? 15000;
    
            $grandTotal = $totalAmount + $shippingFee;
    
            // 4. Simpan order
            $order_id = Order::insertGetId([
                'user_id' => Auth::id(),
                'product_id' => $cartItems[array_key_first($cartItems)]['id'], // Ambil product_id pertama
                'status' => 'pending',
                'total_price' => $grandTotal,
                'payment_method' => $request->payment_selected,
                'courier' => $request->courier_selected,
                'invoice_no' => 'GS-' . time(),
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            // 5. Simpan order items dan KURANGI STOK
            foreach ($cartItems as $item) {
                OrderItem::insert([
                    'order_id' => $order_id,
                    'product_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
    
                // INI BAGIAN PENGURANGAN STOK
                Product::where('id', $item['id'])->decrement('qty', $item['qty']);
            }
    
            // 6. Bersihkan keranjang
            session()->forget('cart');
            DB::commit();
    
            return view('customer.checkout.thanks')->with([
                'message' => 'Order berhasil. Stok produk telah dikurangi.',
                'alert-type' => 'success'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'message' => 'Order gagal: ' . $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }

}