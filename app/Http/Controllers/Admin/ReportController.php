<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use DateTime;

class ReportController extends Controller
{
    // Method untuk menampilkan semua laporan
    public function AdminAllReports()
    {
        $orders = Order::latest()->get(); // Ambil semua pesanan
        return view('admin.all_reports', compact('orders'));
    }

    // Method untuk pencarian laporan berdasarkan tanggal (Admin)
    public function searchByDate(Request $request)
    {
        // Validasi tanggal
        $request->validate([
            'date' => 'required|date', // Validasi format tanggal
        ]);

        // Ambil input tanggal
        $date = $request->input('date');
        
        // Ambil data pesanan berdasarkan tanggal
        $orders = Order::whereDate('created_at', $date)->get();

        // Cek jika data tidak ditemukan
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada laporan ditemukan untuk tanggal ini.');
        }

        // Kirim data ke view
        return view('admin.report', compact('orders'));
    }

    // Method untuk pencarian laporan berdasarkan bulan (Admin)
    public function AdminSearchByMonth(Request $request)
    {
        // Validasi bulan dan tahun
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year_name' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $month = $request->month;
        $year = $request->year_name;

        // Ambil data pesanan berdasarkan bulan dan tahun
        $orders = Order::where('order_month', $month)
            ->where('order_year', $year)
            ->latest()
            ->get();

        return view('admin.backend.report.search_by_month', compact('orders', 'month', 'year'));
    }

    // Method untuk pencarian laporan berdasarkan tahun (Admin)
    public function AdminSearchByYear(Request $request)
    {
        // Validasi tahun
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $year = $request->year;

        // Ambil data pesanan berdasarkan tahun
        $orders = Order::where('order_year', $year)
            ->latest()
            ->get();

        return view('admin.backend.report.search_by_year', compact('orders', 'year'));
    }

    // Method untuk laporan berdasarkan tanggal (Client)
    public function ClientSearchByDate(Request $request)
    {
        // Validasi tanggal
        $request->validate([
            'date' => 'required|date',
        ]);

        // Formatkan tanggal
        $date = new DateTime($request->date);
        $formatDate = $date->format('d F Y');

        // Ambil ID client yang sedang login
        $cid = Auth::guard('client')->id();

        // Ambil data pesanan berdasarkan tanggal
        $orders = Order::where('order_date', $formatDate)
            ->whereHas('OrderItems', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        // Ambil data item pesanan
        $orderItemGroupData = OrderItem::with(['order', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.backend.report.search_by_date', compact('orderItemGroupData', 'formatDate'));
    }

    // Method untuk laporan berdasarkan bulan (Client)
    public function ClientSearchByMonth(Request $request)
    {
        // Validasi bulan dan tahun
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year_name' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $month = $request->month;
        $year = $request->year_name;

        // Ambil ID client yang sedang login
        $cid = Auth::guard('client')->id();

        // Ambil data pesanan berdasarkan bulan dan tahun
        $orders = Order::where('order_month', $month)
            ->where('order_year', $year)
            ->whereHas('OrderItems', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        // Ambil data item pesanan
        $orderItemGroupData = OrderItem::with(['order', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.backend.report.search_by_month', compact('orderItemGroupData', 'month', 'year'));
    }

    // Method untuk laporan berdasarkan tahun (Client)
    public function ClientSearchByYear(Request $request)
    {
        // Validasi tahun
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $year = $request->year;

        // Ambil ID client yang sedang login
        $cid = Auth::guard('client')->id();

        // Ambil data pesanan berdasarkan tahun
        $orders = Order::where('order_year', $year)
            ->whereHas('OrderItems', function ($query) use ($cid) {
                $query->where('client_id', $cid);
            })
            ->latest()
            ->get();

        // Ambil data item pesanan
        $orderItemGroupData = OrderItem::with(['order', 'product'])
            ->whereIn('order_id', $orders->pluck('id'))
            ->where('client_id', $cid)
            ->orderBy('order_id', 'desc')
            ->get()
            ->groupBy('order_id');

        return view('client.backend.report.search_by_year', compact('orderItemGroupData', 'year'));
    }
}
