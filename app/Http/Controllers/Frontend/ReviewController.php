<?php
// app/Http/Controllers/Frontend/ReviewController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        // TODO: Implement review storage
        return back()->with('success', 'Sharh qo\'shildi');
    }

    /**
     * Update review
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement review update
        return back()->with('success', 'Sharh yangilandi');
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        // TODO: Implement review deletion
        return back()->with('success', 'Sharh o\'chirildi');
    }
}
