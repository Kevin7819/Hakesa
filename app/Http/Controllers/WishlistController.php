<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlistItems = auth()->user()
            ->wishlists()
            ->with('product.category')
            ->get();

        // Identify inactive items to remove
        $inactiveIds = $wishlistItems->filter(function ($item) {
            return ! $item->product || ! $item->product->is_active;
        })->pluck('id');

        // Delete inactive items in a single query
        if ($inactiveIds->isNotEmpty()) {
            Wishlist::whereIn('id', $inactiveIds)->delete();
        }

        // Return only valid items
        $wishlistItems = $wishlistItems->filter(function ($item) {
            return $item->product && $item->product->is_active;
        });

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function store(Product $product): JsonResponse
    {
        if (! $product->is_active) {
            return response()->json(['message' => 'Producto no disponible'], 400);
        }

        $wishlist = auth()->user()->wishlists()->firstOrCreate(['product_id' => $product->id]);

        return response()->json([
            'message' => 'Producto agregado a favoritos',
            'count' => auth()->user()->wishlists()->count(),
            'in_wishlist' => true,
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        auth()->user()->wishlists()->where('product_id', $product->id)->delete();

        return response()->json([
            'message' => 'Producto removido de favoritos',
            'count' => auth()->user()->wishlists()->count(),
            'in_wishlist' => false,
        ]);
    }
}
