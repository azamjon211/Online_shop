<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Ruxsat yo\'q. Faqat adminlar kirishi mumkin'
            ], 403);
        }

        return $next($request);
    }
}
