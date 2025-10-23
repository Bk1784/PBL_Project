<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//
class CartController extends Controller
{
    public function AddToCart($id){
        $products = Product::find($id);
 
        $cart = session()->get('cart',[]);
        if (isset($cart[$id])) {
           $cart[$id]['qty']++;
        } else {
           $cart[$id] = [
            'id' => $id,
            'name' => $products->name,
            'image' => $products->image,
            'price' => $products->price,
            'qty' => 1
           ];
        }
        session()->put('cart',$cart);
        $notification = array(
            'message' => 'Add to Cart Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

        public function UpdateCartQuantity(Request $request)
{
    $request->validate([
        'id' => 'required',
        'quantity' => 'required|integer|min:1',
    ]);

    $cart = session()->get('cart', []);

    if (!isset($cart[$request->id])) {
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart',
        ], 404);
    }

    // Update quantity item
    $cart[$request->id]['qty'] = $request->quantity;

    // Hitung ulang total harga & item
    $grandTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
    $totalItems = collect($cart)->sum('qty');

    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'price' => (float) $cart[$request->id]['price'],
        'grandTotal' => (float) $grandTotal,
        'totalItems' => (int) $totalItems,
        'cart' => $cart,
        'message' => 'Cart updated successfully',
    ]);
}

    public function CartRemove(Request $request){
        $request->validate([
            'id' => 'required:integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if(isset($cart[$request->id])){

            unset($cart[$request->id]);
            session()->put('cart', $cart);
            
            $collection = collect($cart);
            $grandTotal = $collection->sum(fn($item) => $item['price'] * $item['qty']);
            $totalItems = $collection->sum('qty');

            return response()->json([
                'success' => true,
                'grandTotal' => (float) $grandTotal,
                'total_items' => (int) $totalItems,
                'cart_count' => count($cart),
                'message' => 'Item berhasil dihapus dari keranjang'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan di keranjang'
        ], 404);
    }
    public function CheckoutProduk(){
        if(Auth::check()){
            $cart = session()->get('cart', []);
            $totalAmount = 0;
            foreach($cart as $car){
                $totalAmount += $car['price'];
            }
            if ($totalAmount > 0) {
                return view('customer.checkout.view_checkout', compact('cart'));
            } else {
                $notification = array(
                    'message' => 'Setidaknya membeli 1 produk',
                    'alert-type' => 'error'
                );
        
                return redirect()->to('/atk_dashboard')->with($notification);
        
            }
            
        }else{
            $notification = array(
                'message' => 'Please Login First',
                'alert-type' => 'success'
            );
    
            return redirect()->route('login')->with($notification);
    
        }
    }//
}
