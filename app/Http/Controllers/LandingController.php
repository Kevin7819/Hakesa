<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $products = Product::active()->with('category')->latest()->take(6)->get();
        $comments = Comment::with('user:id,name')->approved()->latest()->take(5)->get();
        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        return view('welcome', compact('products', 'comments', 'wishlistIds'));
    }
}
