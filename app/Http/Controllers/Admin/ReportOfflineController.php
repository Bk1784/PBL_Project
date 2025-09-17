<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItemClient;
use Illuminate\Http\Request;
use DateTime;

class ReportOfflineController extends Controller
{
    // Method untuk menampilkan semua laporan
    public function AdminAllReports()
    {
        return view('admin.backend.report_offline.all_report');
    }

    // Method untuk pencarian laporan berdasarkan tanggal (Admin)
   public function AdminSearchByDate(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        return redirect()->route('admin.offline.search.bydate.result', [
            'date' => $validated['date'],
        ]);
    }
    public function AdminSearchByDateResult(Request $request)
    {
        $date = new DateTime($request->query('date'));
        $formatDate = $date->format('d F Y');

        $orderDate = OrderItemClient::whereDate('created_at', $date->format('Y-m-d'))
            ->latest()
            ->get();

        return view('admin.backend.report_offline.search_by_date', compact('orderDate', 'formatDate'));
    }

    public function AdminSearchByMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        return redirect()->route('admin.offline.search.bymonth.result', [
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

        $orderMonth = OrderItemClient::whereYear('created_at', $years)
            ->whereMonth('created_at', $months)
            ->latest()
            ->get();

        return view('admin.backend.report_offline.search_by_month', compact('orderMonth', 'months', 'years'));
    }



    // // Method untuk pencarian laporan berdasarkan tahun (Admin)
    public function AdminSearchByYear(Request $request)
    {
        // Validasi tahun
        $request->validate([
            'year' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        return redirect()->route('admin.offline.search.byyear.result', ['year' => $request->year]);
    }

    public function AdminSearchByYearResult($year)
    {
        $orderYears = OrderItemClient::whereYear('created_at', $year)
            ->latest()
            ->get();

        return view('admin.backend.report_offline.search_by_year', compact('orderYears', 'year'));
    }
}