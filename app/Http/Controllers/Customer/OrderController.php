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
        $orders = Order::where('client_id', Auth::id())->latest()->get();
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
        $orderItem = OrderItem::where('order_id', $order_id)->get();
        $totalPrice = $orderItem->sum(fn($item) => $item->price * $item->qty);

        $pdf = PDF::loadView('order.invoice_download', compact('order', 'orderItem', 'totalPrice'))
                  ->setPaper('A4');

        return $pdf->download('invoice_' . $order->invoice_no . '.pdf');
    }


    public function CashOrder (Request $request) {
        $validateData = $request->validate([
            'courier_selected'=>'required',
            'payment_selected'=>'required',
        ]);

        $cart = session()->get('cart',[]);
        $totalAmount = 0;

        foreach($cart as $cart) {
            $totalAmount += ($cart['price'] * $cart['qty']);
        }

        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'client_id' => Auth::id(),
            'product_id' => $cart ['id'],
            'quantity' =>  $cart ['qty'],
            'status' =>'pending',
            'total_price' => $totalAmount,
            'payment_method' => $request->payment_selected,
            'courier' => $request->courier_selected,
            'invoice_no' => 'Galaxy Store' .mt_rand(10000000,99999999),
            'notes' => null,
            'created_at' => Carbon::now(),
        ]);

        $carts = session()->get('cart',[]);
        foreach ($carts as $cart) {
            OrderItem::insert([
                'order_id' => $order_id,
                'client_id' => Auth::id(),
                'product_id' => $cart['id'],
                'qty' => $cart['qty'],
                'price' => $cart['price']
            ]);
        }

        if (Session::has('cart')) {
            Session::forget('cart');
        }

        $notification = array(
            'message' => 'Order Successfully',
            'alert-type' => 'success'
        );

        return view('customer.checkout.thanks')->with($notification);
    }
}