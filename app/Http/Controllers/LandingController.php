<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        // Productos destacados cacheados por 5 min
        $products = Cache::remember('homepage-products', now()->addMinutes(5), function () {
            return Product::active()->with('category')->latest()->take(6)->get();
        });

        // Comentarios cacheados por 15 min
        $comments = Cache::remember('homepage-comments', now()->addMinutes(15), function () {
            return Comment::with('user:id,name')->approved()->latest()->take(5)->get();
        });

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        return view('welcome', compact('products', 'comments', 'wishlistIds'));
    }
}
