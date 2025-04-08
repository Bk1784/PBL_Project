<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
