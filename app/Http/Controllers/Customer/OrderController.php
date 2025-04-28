<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    // Ambil isi keranjang
    $cartItems = session()->get('cart', []);
    $totalAmount = 0;

    // Hitung total harga dari semua item di keranjang
    foreach ($cartItems as $item) {
        $totalAmount += ($item['price'] * $item['qty']);
    }

    // Daftar harga pengiriman berdasarkan nama kurir
    $shippingCosts = [
        'JNE Reguler' => 15000,
        'J&T Express' => 17000,
        'SiCepat' => 13000,
        'POS Indonesia' => 14000,
    ];

    // Ambil nama kurir dan cari biaya kirim
    $courierName = $request->courier_selected;
    $shippingFee = $shippingCosts[$courierName] ?? 15000;

    // Total keseluruhan termasuk ongkir
    $grandTotal = $totalAmount + $shippingFee;

    // Simpan data ke tabel orders
    $order_id = Order::insertGetId([
        'user_id' => Auth::id(),
        'product_id' => $item['id'],
        'status' => 'pending',
        'total_price' => $grandTotal,
        'payment_method' => $request->payment_selected,
        'courier' => $courierName,
        'invoice_no' => 'Galaxy Store' . mt_rand(10000000, 99999999),
        'notes' => null,
        'created_at' => Carbon::now(),
    ]);


    foreach ($cartItems as $item) {
        OrderItem::insert([
            'order_id' => $order_id,
            'product_id' => $item['id'],
            'qty' => $item['qty'],
            'price' => $item['price'],
        ]);
    }

    

    // Hapus isi keranjang setelah order berhasil
    session()->forget('cart');

    $notification = array(
        'message' => 'Order Successfully',
        'alert-type' => 'success'
    );

    // Redirect dengan notifikasi
    return view('customer.checkout.thanks')->with($notification);
}

}