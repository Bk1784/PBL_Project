<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function DetailProduk($id){
        $product = Product::find($id);
        return view('customer.detail_produk', compact('product'));
    }
    //end method
}
