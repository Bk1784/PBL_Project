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
        return view('admin.backend.report.all_report');
    }

    // Method untuk pencarian laporan berdasarkan tanggal (Admin)
   public function AdminSearchByDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        return redirect()->route('admin.search.bydate.result', [
            'date' => $validated['date'],
        ]);
    }
    public function AdminSearchByDateResult(Request $request)
    {
        $date = new DateTime($request->query('date'));
        $formatDate = $date->format('d F Y');

        $orderDate = Order::whereDate('created_at', $date->format('Y-m-d'))
            ->latest()
            ->get();

        return view('admin.backend.report.search_by_date', compact('orderDate', 'formatDate'));
    }

    public function AdminSearchByMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        return redirect()->route('admin.search.bymonth.result', [
            'month' => $validated['month'],
            'year' => $validated['year'],
        ]);
    }

    public function AdminSearchByMonthResult(Request $request)
    {
        $monthName = $request->query('month');
        $years = $request->query('year');

        $month = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $months = $month[$monthName] ?? 1;

        $orderMonth = Order::whereYear('created_at', $years)
            ->whereMonth('created_at', $months)
            ->latest()
            ->get();

        return view('admin.backend.report.search_by_month', compact('orderMonth', 'months', 'years'));
    }



    // // Method untuk pencarian laporan berdasarkan tahun (Admin)
    public function AdminSearchByYear(Request $request)
    {
        // Validasi tahun
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        return redirect()->route('admin.search.byyear.result', ['year' => $request->year]);
    }

    public function AdminSearchByYearResult($year)
    {
        $orderYears = Order::whereYear('created_at', $year)
            ->latest()
            ->get();

        return view('admin.backend.report.search_by_year', compact('orderYears', 'year'));
    }


    // // Method untuk laporan berdasarkan tanggal (Client)
    // public function ClientSearchByDate(Request $request)
    // {
    //     // Validasi tanggal
    //     $request->validate([
    //         'date' => 'required|date',
    //     ]);

    //     // Formatkan tanggal ke Y-m-d supaya cocok dengan penyimpanan database
    //     $formatDate = Carbon::parse($request->date)->format('Y-m-d');

    //     // Ambil ID client yang sedang login
    //     $cid = Auth::guard('client')->id();

    //     // Ambil data pesanan berdasarkan tanggal
    //     $orders = Order::whereDate('order_date', $formatDate)
    //         ->whereHas('orderItems', function ($query) use ($cid) {
    //             $query->where('client_id', $cid);
    //         })
    //         ->latest()
    //         ->get();

    //     // Ambil data item pesanan
    //     $orderItemGroupData = OrderItem::with(['order', 'product'])
    //         ->whereIn('order_id', $orders->pluck('id'))
    //         ->where('client_id', $cid)
    //         ->orderBy('order_id', 'desc')
    //         ->get()
    //         ->groupBy('order_id');

    //     return view('client.backend.report.search_by_date', compact('orderItemGroupData', 'formatDate'));
    // }

    // // Method untuk pencarian laporan berdasarkan bulan (Admin) berdasarkan OrderItem
    // public function AdminSearchByMonth_OrderItem(Request $request)
    // {
    //     $request->validate([
    //         'month' => 'required|integer|between:1,12',
    //         'year' => 'required|integer|min:2000|max:' . date('Y'),
    //     ]);

    //     $month = $request->month;
    //     $year = $request->year;

    //     $orderItemGroupData = OrderItem::with('order')
    //         ->whereHas('order', function ($query) use ($month, $year) {
    //             $query->whereYear('order_date', $year)
    //                   ->whereMonth('order_date', $month);
    //         })
    //         ->orderBy('order_id', 'ASC')
    //         ->get();

    //     return view('admin.backend.report.search_by_month', compact('orderItemGroupData', 'month', 'year'));
    // }

    // // Method untuk laporan berdasarkan tahun (Client)
    // public function ClientSearchByYear(Request $request)
    // {
    //     // Validasi tahun
    //     $request->validate([
    //         'year' => 'required|integer|min:1900|max:' . date('Y'),
    //     ]);

    //     $year = $request->year;

    //     // Ambil ID client yang sedang login
    //     $cid = Auth::guard('client')->id();

    //     // Ambil data pesanan berdasarkan tahun
    //     $orders = Order::whereYear('order_date', $year)
    //         ->whereHas('orderItems', function ($query) use ($cid) {
    //             $query->where('client_id', $cid);
    //         })
    //         ->latest()
    //         ->get();

    //     // Ambil data item pesanan
    //     $orderItemGroupData = OrderItem::with(['order', 'product'])
    //         ->whereIn('order_id', $orders->pluck('id'))
    //         ->where('client_id', $cid)
    //         ->orderBy('order_id', 'desc')
    //         ->get()
    //         ->groupBy('order_id');

    //     return view('client.backend.report.search_by_year', compact('orderItemGroupData', 'year'));
    // }
}