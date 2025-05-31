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
    public function AdminSearchByDate(Request $request) 
    {
        // Validasi tanggal
        $request->validate([
            'date' => 'required|date', // Validasi format tanggal
        ]);

        // Ambil input tanggal
        $date = $request->input('date');
        
        // Ambil data pesanan berdasarkan tanggal (gunakan whereDate supaya cocok format)
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
            'month' => 'required|string',
            'year_name' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        $monthName = $request->month;
        $year = $request->year_name;

        // Konversi nama bulan ke angka
        $months = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];
        
        $month = $months[$monthName] ?? 1;

        // Ambil data pesanan berdasarkan bulan dan tahun
        // *Asumsi kolom order_date ada di tabel orders dan formatnya tanggal penuh
        $orders = Order::whereYear('order_date', $year)
            ->whereMonth('order_date', $month)
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
        // *Asumsi order_date adalah tanggal di tabel orders
        $orders = Order::whereYear('order_date', $year)
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

        // Formatkan tanggal ke Y-m-d supaya cocok dengan penyimpanan database
        $formatDate = Carbon::parse($request->date)->format('Y-m-d');

        // Ambil ID client yang sedang login
        $cid = Auth::guard('client')->id();

        // Ambil data pesanan berdasarkan tanggal
        $orders = Order::whereDate('order_date', $formatDate)
            ->whereHas('orderItems', function ($query) use ($cid) {
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

    // Method untuk pencarian laporan berdasarkan bulan (Admin) berdasarkan OrderItem
    public function AdminSearchByMonth_OrderItem(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $month = $request->month;
        $year = $request->year;

        $orderItemGroupData = OrderItem::with('order')
            ->whereHas('order', function ($query) use ($month, $year) {
                $query->whereYear('order_date', $year)
                      ->whereMonth('order_date', $month);
            })
            ->orderBy('order_id', 'ASC')
            ->get();

        return view('admin.backend.report.search_by_month', compact('orderItemGroupData', 'month', 'year'));
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
        $orders = Order::whereYear('order_date', $year)
            ->whereHas('orderItems', function ($query) use ($cid) {
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