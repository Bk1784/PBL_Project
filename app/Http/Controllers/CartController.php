<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $request->name,
                "price" => $request->price,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => 'Product added to cart']);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->id;

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }

            session()->put('cart', $cart);
        }

        return response()->json(['success' => 'Product removed from cart']);
    }
    public function CheckoutToko(){
        return view('customer.checkout.view_checkout');
        // if (Auth::check()) {
        //     $cart = session()->get('cart',[]);
        //     $totalAmount = 0;
        //     foreach ($cart as $car) {
        //         $totalAmount += $car['price'];
        //     }
        //     if ($totalAmount < 0) {
        //        return view('checkout.view_checkout', compact('cart'));
        //     } else {
        //         $notification = array(
        //             'message' => 'Shopping at list one item',
        //             'alert-type' => 'error'
        //         ); 
        //         return redirect()->to('/dashboard')->with($notification);
        //     } 
        // }else{
        //     $notification = array(
        //         'message' => 'Login terlebih dahulu',
        //         'alert-type' => 'success'
        //     );
        //     return redirect()->route('login')->with($notification); 
        // } 
    }

    public function updateQuantity(Request $request)
    {
      
        $id = $request->id;
        $quantity = $request->quantity;
    
        // Ambil cart dari session
        $cart = session()->get('cart');
    
        // Jika item ada di dalam cart, update quantity-nya
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart); // Simpan kembali ke session
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully'
        ]);
    }
    
}
