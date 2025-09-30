<?php

// app/Http/Controllers/PurchaseController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchases;
use Illuminate\Http\Request;
use PDF; // Tambahkan ini jika menggunakan barryvdh/laravel-dompdf

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
        Purchases::create([
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
    
    public function index(Request $request)
    {
        $month = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        $currentMonth = date('n');
        $selectedMonth = $request->get('month', $month[$currentMonth-1]);
        $selectedMonthIndex = array_search($selectedMonth, $month) + 1;

        $purchases = Purchases::with(['product'])
            ->whereMonth('created_at', $selectedMonthIndex)
            ->latest()
            ->get();


        return view('admin.backend.purchases.all', compact('purchases', 'month', 'selectedMonth'));
    }

    public function exportPdf(Request $request)
    {
        $month = [
            'January', 'February', 'March', 'April',
            'May', 'June', 'July', 'August',
            'September', 'October', 'November', 'December'
        ];

        $selectedMonth = $request->get('month', $month[date('n')-1]);
        $selectedMonthIndex = array_search($selectedMonth, $month) + 1;

        $purchases = Purchases::with(['product'])
            ->whereMonth('created_at', $selectedMonthIndex)
            ->latest()
            ->get();

        $pdf = PDF::loadView('admin.backend.purchases.pdf', compact('purchases', 'selectedMonth'));
        return $pdf->download('laporan_pembelian_'.$selectedMonth.'.pdf');
    }
}
