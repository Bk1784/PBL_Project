<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    // Menyimpan review
    public function StoreReview(Request $request)
    {
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'rating'  => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }
}
