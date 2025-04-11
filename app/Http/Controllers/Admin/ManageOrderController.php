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
