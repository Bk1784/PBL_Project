<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;

class ManageRatingController extends Controller
{
    public function AdminRating(Request $request)
    {
        $query = DB::table('order_ratings')
            ->join('orders', 'order_ratings.order_id', '=', 'orders.id')
            ->join('users', 'order_ratings.user_id', '=', 'users.id')
            ->select(
                'orders.invoice_no',
                'users.name as customer_name',
                'order_ratings.rating',
                'order_ratings.comment',
                'order_ratings.created_at'
            );

        // Filter rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('order_ratings.rating', $request->rating);
        }

        // Sorting
        if ($request->sort == 'oldest') {
            $query->orderBy('order_ratings.created_at', 'asc');
        } else {
            $query->orderBy('order_ratings.created_at', 'desc');
        }

        $ratings = $query->paginate(10);

        // Statistik
        $avgRating = round(DB::table('order_ratings')->avg('rating'), 1);
        $totalReviews = DB::table('order_ratings')->count();
        $distribution = DB::table('order_ratings')
            ->select('rating', DB::raw('count(*) as jumlah'))
            ->groupBy('rating')
            ->pluck('jumlah', 'rating');

        return view('admin.backend.rating.admin_rating', compact('ratings', 'avgRating', 'totalReviews', 'distribution'));
    }
}