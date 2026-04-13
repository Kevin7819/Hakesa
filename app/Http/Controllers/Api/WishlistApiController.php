<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WishlistResource;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistApiController extends Controller
{
    /**
     * List user's wishlist (only active products).
     */
    public function index(): JsonResponse
    {
        $wishlist = Wishlist::where('user_id', Auth::id())
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->with('product')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => WishlistResource::collection($wishlist),
        ]);
    }

    /**
     * Add a product to wishlist.
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        if (! $product->is_active) {
            return response()->json([
                'message' => 'Producto no disponible.',
            ], 400);
        }

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'message' => 'Producto agregado a favoritos.',
            'data' => new WishlistResource($wishlist->load('product')),
        ], 201);
    }

    /**
     * Remove a product from wishlist.
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'message' => 'Producto removido de favoritos.',
        ]);
    }
}
