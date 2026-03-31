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
        $comments = Comment::with('user')->approved()->latest()->take(5)->get();

        return view('welcome', compact('products', 'comments'));
    }
}
