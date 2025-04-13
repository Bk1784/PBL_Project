<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Product;
use Illuminate\Support\Facades\Session; 
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

class ManageOrderController extends Controller
{

    public function PendingOrders()
    {
        $orders = Order::where('status', 'pending')->latest()->get();
        return view('admin.backend.orders.pending_orders', compact('orders'));
    }

    public function ConfirmOrders()
    {
        $orders = Order::where('status', 'confirmed')->latest()->get();
        return view('admin.backend.orders.confirm_orders', compact('orders'));
    }

    public function ProcessingOrders()
    {
        $orders = Order::where('status', 'processing')->latest()->get();
        return view('admin.backend.orders.processing_orders', compact('orders'));
    }

    public function DeliveredOrders()
    {
        $orders = Order::where('status', 'delivered')->latest()->get();
        return view('admin.backend.orders.delivered_orders', compact('orders'));
    }

    public function OrderDetails($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $orderItems = OrderItem::where('order_id', $id)->get();

        return view('admin.backend.orders.order_details', compact('order', 'orderItems'));
    }
    
    public function downloadInvoice($order_id)
    {
        $order = Order::findOrFail($order_id);
        $orderItem = OrderItem::where('order_id', $order_id)->get();

        // Total price
        $totalPrice = $orderItem->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $pdf = PDF::loadView('admin.invoice', compact('order', 'orderItem', 'totalPrice'))
                  ->setPaper('A4');

        return $pdf->download('invoice_'.$order->invoice_no.'.pdf');
    }
}