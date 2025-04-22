<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    $tanggal = $request->input('tanggal');

    $penjualan = \App\Models\Penjualan::with('barang')
        ->when($tanggal, function ($query, $tanggal) {
            return $query->whereDate('tanggal_penjualan', $tanggal);
        })
        ->get();

    return view('laporan.index', compact('penjualan', 'tanggal'));
}
public function barangTerjual(Request $request)
{
    $tanggal = $request->input('tanggal');

    $penjualan = \App\Models\Penjualan::with('barang')
        ->when($tanggal, function ($query, $tanggal) {
            return $query->whereDate('tanggal_penjualan', $tanggal);
        })
        ->get();

    return view('laporan.index', compact('penjualan', 'tanggal'));
}
}
