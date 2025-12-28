<?php
// app/Http/Controllers/Api/ReviewController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    // GET /api/products/{productId}/reviews
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->approved()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        // Rating statistikasi
        $stats = Review::where('product_id', $productId)
            ->approved()
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(rating) as average'),
                DB::raw('SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star'),
                DB::raw('SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star'),
                DB::raw('SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star'),
                DB::raw('SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star'),
                DB::raw('SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star')
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
                'stats' => $stats
            ]
        ]);
    }

    // POST /api/reviews
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Bir mahsulotga faqat bitta sharh
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Siz bu mahsulotga allaqachon sharh qoldirgan ekansiz'
            ], 400);
        }

        // Agar order_id berilgan bo'lsa, tekshirish
        $isVerified = false;
        if (isset($validated['order_id'])) {
            $order = Order::where('id', $validated['order_id'])
                ->where('user_id', Auth::id())
                ->whereHas('items', function($query) use ($validated) {
                    $query->where('product_id', $validated['product_id']);
                })
                ->first();

            if ($order && $order->status === 'delivered') {
                $isVerified = true;
            }
        }

        DB::beginTransaction();
        try {
            $review = Review::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'order_id' => $validated['order_id'] ?? null,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'is_verified' => $isVerified,
                'is_approved' => false // Admin tasdiqlashi kerak
            ]);

            // Mahsulot ratingini yangilash
            $this->updateProductRating($validated['product_id']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sharh qo\'shildi. Tekshiruvdan o\'tgach ko\'rinadi',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/reviews/{id}
    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $review->update($validated);
            $review->is_approved = false; // Qayta tekshirish uchun
            $review->save();

            // Ratingni yangilash
            $this->updateProductRating($review->product_id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sharh yangilandi',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // DELETE /api/reviews/{id}
    public function destroy($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $productId = $review->product_id;
            $review->delete();

            // Ratingni yangilash
            $this->updateProductRating($productId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sharh o\'chirildi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    // PUT /api/reviews/{id}/approve (Admin uchun)
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        // Ratingni yangilash
        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Sharh tasdiqlandi',
            'data' => $review
        ]);
    }

    // GET /api/reviews/my
    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('product:id,name,slug')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    // Helper method - Mahsulot ratingini yangilash
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
