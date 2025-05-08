<?php

// app/Http/Controllers/PurchaseController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchases;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('admin.backend.purchases.add', compact('products'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0'
        ]);
        
        // Simpan pembelian
        $purchase = Purchases::create([
            'product_id' => $validated['product_id'],
            'qty' => $validated['qty'],
            'total_price' => $validated['total_price'],
        ]);
        
        // Update stok produk
        $product = Product::find($validated['product_id']);
        $product->qty += $validated['qty'];
        $product->save();
        
        return redirect()->route('admin.backend.purchases.all')->with('success', 'Pembelian berhasil dicatat dan stok bertambah');
    }
    
    public function index()
    {
        $purchases = Purchases::with(['product'])->latest()->get();
        return view('admin.backend.purchases.all', compact('purchases'));
    }
}
