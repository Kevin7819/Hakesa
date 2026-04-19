<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        // Productos destacados
        $products = Product::active()->with('category')->latest()->take(6)->get();

        // Comentarios
        $comments = Comment::with('user:id,name')->approved()->latest()->take(5)->get();

        // Anuncios activos y no expirados
        $announcements = Announcement::visible()
            ->orderByDesc('event_date')
            ->get();

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        return view('welcome', compact('products', 'comments', 'wishlistIds', 'announcements'));
    }
}
