<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories with all views (except admin)
        View::composer('*', function ($view) {
            // Skip admin routes
            if (!request()->is('admin/*')) {
                try {
                    $categories = Category::with('children')
                        ->whereNull('parent_id')
                        ->where('is_active', 1)
                        ->orderBy('order')
                        ->get();

                    $view->with('categories', $categories);
                } catch (\Exception $e) {
                    // If database not ready or error, provide empty collection
                    $view->with('categories', collect());
                }
            }
        });
    }
}
