<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;

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

    // ✅ Data transaksi detail (per order/invoice)
    $orderDate = Order::whereDate('created_at', $date->format('Y-m-d'))
        ->with(['orderItems.product'])
        ->latest()
        ->get();

    // ✅ Data rekap produk per tanggal
    $rekapProduk = OrderItem::with('product')
        ->whereDate('created_at', $date->format('Y-m-d'))
        ->selectRaw('
            product_id,
            SUM(qty) as penjualan,
            SUM(price * qty) as komisi_kotor,
            SUM(price * qty * 0.9) as komisi_bersih
        ')
        ->groupBy('product_id')
        ->get();

    return view('admin.backend.report.search_by_date', compact(
        'orderDate',
        'rekapProduk',
        'formatDate'
    ));
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

    // Mapping nama bulan ke angka
    $month = [
        'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
        'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
        'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
    ];

    $months = $month[$monthName] ?? 1;

    // ✅ Data transaksi detail (per order/invoice)
    $orderMonth = Order::whereYear('created_at', $years)
        ->whereMonth('created_at', $months)
        ->with(['orderItems.product']) // Eager load orderItems and their associated products
        ->latest()
        ->get();

    // ✅ Data rekap produk
    $rekapProduk = OrderItem::with('product')
        ->selectRaw('
            product_id,
            SUM(qty) as penjualan,
            SUM(price * qty) as komisi_kotor,
            SUM(price * qty * 0.9) as komisi_bersih
        ')
        ->groupBy('product_id')
        ->get();



    return view('admin.backend.report.search_by_month', compact(
        'orderMonth',
        'rekapProduk',
        'months',
        'years',
        'monthName'
    ));
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
    // Pastikan tahun valid
    if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
        abort(404, 'Tahun tidak valid.');
    }

    // ✅ Data transaksi detail (per order/invoice)
    $orderYears = Order::whereYear('created_at', $year)
        ->with(['orderItems.product']) // load orderItems + product
        ->latest()
        ->get();

    // ✅ Data rekap produk
    $rekapProduk = OrderItem::with('product')
        ->whereHas('order', function ($query) use ($year) {
            $query->whereYear('created_at', $year);
        })
        ->selectRaw('
            product_id,
            SUM(qty) as penjualan,
            SUM(price * qty) as komisi_kotor,
            SUM(price * qty * 0.9) as komisi_bersih
        ')
        ->groupBy('product_id')
        ->get();

    return view('admin.backend.report.search_by_year', compact(
        'orderYears',
        'rekapProduk',
        'year'
    ));
}


}