<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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

        // return response()->json($cart);
        $notification = array(
            'message' => 'Add to Cart Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }

    public function UpdateCartQuantity(Request $request) {
        $cart = session()->get('cart', []);
        $grandTotal = 0;
        $totalItems = 0;
    
        if(isset($cart[$request->id])) {
            // Update quantity
            $cart[$request->id]['quantity'] = $request->quantity;
            
            // Hitung ulang total
            foreach($cart as $item) {
                $grandTotal += $item['price'] * $item['quantity'];
                $totalItems += $item['quantity'];
            }
            
            session()->put('cart', $cart);
    
            // Return JSON response
            return response()->json([
                'success' => true,
                'price' => (float)$cart[$request->id]['price'],
                'grandTotal' => (float)$grandTotal,
                'totalItems' => (int)$totalItems,
                'cart' => $cart,
                'message' => 'Cart Updated Successfully'
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ], 404);
    }

    public function CartRemove(Request $request)
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $totalItems = 0;

        if(isset($cart[$request->id])) {
            // Hapus item dari cart
            unset($cart[$request->id]);
            session()->put('cart', $cart);

            // Hitung ulang total
            foreach($cart as $item) {
                $total += $item['price'] * $item['qty'];
                $totalItems += $item['qty'];
            }

            return response()->json([
                'success' => true,
                'grandTotal' => $total,
                'cartCount' => count($cart),
                'message' => 'Item berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item tidak ditemukan'
        ], 404);
    }

    public function CheckoutView(){
        return view('customer.checkout.view_checkout');
    }


}
