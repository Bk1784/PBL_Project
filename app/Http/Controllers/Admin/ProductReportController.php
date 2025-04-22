<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductReportController extends Controller
{
    public function report()
    {
        // Ambil data produk yang sering terjual (diurutkan dari jumlah terjual terbesar)
        $products = Product::orderByDesc('sold_count')->get();

        // Kirim ke view laporan.blade.php
        return view('admin.laporan', compact('products'));
    }
}
