<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlistItems = auth()->user()
            ->wishlists()
            ->with('product.category')
            ->get()
            ->filter(function ($item) {
                if (! $item->product || ! $item->product->is_active) {
                    $item->delete();

                    return false;
                }

                return true;
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
