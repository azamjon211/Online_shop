<?php
// app/Http/Controllers/Admin/AdminReviewController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        // Update product rating
        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Sharh tasdiqlandi'
        ]);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $productId = $review->product_id;

        $review->delete();

        // Update product rating
        $this->updateProductRating($productId);

        return redirect()->back()->with('success', 'Sharh o\'chirildi');
    }

    private function updateProductRating($productId)
    {
        $stats = Review::where('product_id', $productId)
            ->approved()
            ->select(
                DB::raw('AVG(rating) as average'),
                DB::raw('COUNT(*) as total')
            )
            ->first();

        Product::where('id', $productId)->update([
            'rating' => $stats->average ?? 0,
            'reviews_count' => $stats->total ?? 0
        ]);
    }
}
