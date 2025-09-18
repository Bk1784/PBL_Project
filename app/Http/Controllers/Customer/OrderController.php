<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Midtrans\Snap;
use App\Helpers\MidtransConfig;



class OrderController extends Controller
{
    public function index()
{
    $orders = Order::where('user_id', Auth::id())
                   ->where('status', '!=', 'refunded') // sembunyikan pesanan refund
                   ->latest()
                   ->get();

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
    $order = Order::where('id', $order_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $orderItem = OrderItem::with(['product', 'order.user'])
        ->where('order_id', $order_id)
        ->get();

    // Sesuaikan dengan value dari DB (bukan label dropdown)
    $shippingCosts = [
        'jne' => 2500,
        'jnt' => 5000,
        'sicepat' => 2000,
        'pos' => 7000,
    ];

    $courierNames = [
        'jne' => 'JNE Reguler',
        'jnt' => 'J&T Express',
        'sicepat' => 'SiCepat',
        'pos' => 'POS Indonesia',
    ];

    $courierCode = $order->courier; // Ex: 'jne'
    $shippingFee = $shippingCosts[$courierCode] ?? 0;
    $courierDisplayName = $courierNames[$courierCode] ?? 'Kurir Tidak Dikenal';

    $Price = $orderItem->sum(fn($item) => $item->price * $item->qty);
    $totalAmount = $Price + $shippingFee;

    $pdf = PDF::loadView('order.invoice_download', compact(
        'order', 'orderItem', 'totalAmount', 'Price', 'shippingFee', 'courierDisplayName'
    ))->setPaper('A4');

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

        // 3. Hitung ongkir (asumsi tetap dihitung berdasarkan pilihan kurir)
        $shippingFee = [
            'JNE Reguler' => 2500,
            'J&T Express' => 5000,
            'SiCepat' => 2000,
            'POS Indonesia' => 7000,
        ][$request->courier_selected] ?? 0;

        $grandTotal = $totalAmount + $shippingFee;

        // 4. Simpan order (payment_method dipastikan COD)
        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'product_id' => $cartItems[array_key_first($cartItems)]['id'],
            'status' => 'pending',
            'total_price' => $grandTotal,
            'payment_method' => 'COD', // Sudah pasti COD
            'courier' => $request->courier_selected,
            'invoice_no' => 'GS-' . time(),
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5. Simpan order items dan kurangi stok
        foreach ($cartItems as $item) {
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Product::where('id', $item['id'])->decrement('qty', $item['qty']);
        }

        // 6. Bersihkan keranjang
        session()->forget('cart');
        DB::commit();

        return view('customer.checkout.thanks')->with([
            'message' => 'Order berhasil dengan metode COD. Stok produk telah dikurangi.',
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

public function getMidtransToken(Request $request)
{
    DB::beginTransaction();
    try {
        \Log::info('Midtrans Token Request Started');
        MidtransConfig::config();

        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            \Log::warning('Keranjang kosong saat meminta token Midtrans');
            return response()->json(['error' => 'Keranjang kosong!'], 400);
        }

        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);

            if (!$product) {
                throw new \Exception("Produk ID {$item['id']} tidak ditemukan");
            }

            if ($product->qty < $item['qty']) {
                throw new \Exception("Stok {$product->name} tidak mencukupi");
            }

            $totalAmount += $item['price'] * $item['qty'];
        }

        // Fix: gunakan value (jne, jnt, dll) dari <select>
        $shippingFee = match($request->courier_selected) {
            'jne' => 2500,
            'jnt' => 5000,
            'sicepat' => 2000,
            'pos' => 7000,
            default => 0
        };

        $grandTotal = $totalAmount + $shippingFee;
        $invoice = 'GS-' . time();

        // Simpan Order
        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'product_id' => $cartItems[array_key_first($cartItems)]['id'],
            'status' => 'pending',
            'total_price' => $grandTotal,
            'payment_method' => 'Midtrans',
            'courier' => $request->courier_selected,
            'invoice_no' => $invoice,
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simpan detail order dan kurangi stok
        foreach ($cartItems as $item) {
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Product::where('id', $item['id'])->decrement('qty', $item['qty']);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $invoice,
                'gross_amount' => $grandTotal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '081234567890',
            ],
            'item_details' => [],
        ];

        foreach ($cartItems as $item) {
            $params['item_details'][] = [
                'id' => $item['id'],
                'price' => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name' => \Str::limit($item['name'], 50),
            ];
        }

        // Tambahkan ongkir sebagai item
        $params['item_details'][] = [
            'id' => 'SHIPPING',
            'price' => (int) $shippingFee,
            'quantity' => 1,
            'name' => 'Ongkos Kirim - ' . strtoupper($request->courier_selected),
        ];

        \Log::info('Midtrans Params', $params);

        $snapToken = Snap::getSnapToken($params);
        \Log::info('Snap Token generated: ' . $snapToken);

        DB::commit();

        // Hapus keranjang setelah transaksi berhasil
        session()->forget('cart');

        return response()->json(['snapToken' => $snapToken]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Midtrans Snap Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function thanks(){
    return view('customer.checkout.thanks');
}

public function TampilanRating($id){
    $order = Order::findOrFail($id);
    return view('customer.rating.rate', compact('order'));
}

public function KirimRating(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'rating'  => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    // Cari order berdasarkan ID
    $order = Order::findOrFail($id);

    // Pastikan user yang login adalah pemilik order
    if ($order->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Anda tidak berhak memberi rating untuk pesanan ini.');
    }

    // Cek apakah rating untuk order ini sudah ada
    $existing = \DB::table('order_ratings')
        ->where('order_id', $order->id)
        ->where('user_id', auth()->id())
        ->first();

    if ($existing) {
        return redirect()->route('customer.orders.all_orders')
            ->with('error', 'Anda sudah memberi rating untuk pesanan ini.');
    }

    // Simpan rating ke tabel order_ratings
    \DB::table('order_ratings')->insert([
        'order_id'   => $order->id,
        'user_id'    => auth()->id(),
        'rating'     => $request->rating,
        'comment'    => $request->comment,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('customer.orders.all_orders')
        ->with('success', 'Terima kasih sudah memberi rating!');
}

    public function RefundOrder(Request $request, $id)
{
    try {
        $request->validate([
            'refund_reason' => 'required|string|max:500',
            'refund_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status !== 'completed') {
            return response()->json(['success' => false, 'message' => 'Hanya pesanan completed yang bisa direfund.'], 422);
        }

        if (\App\Models\Refund::where('order_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Refund untuk pesanan ini sudah diajukan.'], 409);
        }

        $imagePath = null;
        if ($request->hasFile('refund_image')) {
            $imagePath = $request->file('refund_image')->store('refunds', 'public');
        }

        // simpan refund
        \App\Models\Refund::create([
            'order_id' => $id,
            'user_id' => Auth::id(),
            'refund_reason' => $request->refund_reason,
            'refund_image' => $imagePath,
            'status' => 'pending',
        ]);

        // update status order jadi refunded
        $order->update(['status' => 'refunded']);

        return response()->json(['success' => true, 'message' => 'Refund berhasil diajukan.'], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['success'=>false, 'message'=>'Validasi gagal','errors'=>$e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('RefundOrder error: '.$e->getMessage()."\n".$e->getTraceAsString());
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
    }
}



    public function AllRefund()
    {
        $refunds = \App\Models\Refund::with(['order', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.orders.all_refund', compact('refunds'));
    }

}
