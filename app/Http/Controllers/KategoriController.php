<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // pakai Product, bukan Produk

class KategoriController extends Controller
{
    public function dekorasi()
    {
        $dekorasi = Product::where('slug', 'dekorasi')->get();

        return view('kategori.dekorasi', compact('dekorasi'));
    }
}
