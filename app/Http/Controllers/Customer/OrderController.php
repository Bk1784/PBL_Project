<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function AllOrders()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('customer.orders.all_orders', compact('orders'));
    }

    public function OrderDetails($id)
    {
        $order = Order::with('orderItems')->where('user_id', Auth::id())->findOrFail($id);
        return view('customer.orders.order_details', compact('order'));
    }

    // Batalkan pesanan (jika masih dalam status tertentu)
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
            return redirect()->back()->with('success', 'Pesanan berhasil konfirmasi diterima.');
        }

        return redirect()->back()->with('error', 'Pesanan belum bisa konfirmasi diterima.');
    }

    public function downloadInvoice($order_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', Auth::id())->firstOrFail();
        $orderItem = OrderItem::where('order_id', $order_id)->get();
        $totalPrice = $orderItem->sum(fn($item) => $item->price * $item->qty);

        $pdf = PDF::loadView('customer.invoice', compact('order', 'orderItem', 'totalPrice'))
                  ->setPaper('A4');

        return $pdf->download('invoice_'.$order->invoice_no.'.pdf');
    }
}
=======
use Illuminate\Support\Facades\Auth; 
 use App\Models\Product;
 use Illuminate\Support\Facades\Session; 
 use App\Models\Order;
 use App\Models\OrderItem;

class OrderController extends Controller
{
    
    public function CashOrder(){
        
        
    }
}
>>>>>>> 54f8835e0daa3c33b3ac5b3711778088801a8a57
