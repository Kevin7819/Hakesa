<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Product::query()
            ->where('is_active', true)
            ->select(['id', 'name', 'description', 'price', 'image', 'category_id', 'service_type'])
            ->with('category:id,name');

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $search = addcslashes($request->search, '%_');
            $query->where('name', 'like', "%{$search}%");
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por precio
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Orden
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->orderByDesc('id'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)
            ->select(['id', 'name', 'slug', 'sort_order'])
            ->orderBy('sort_order')
            ->get();

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        // Calculate dynamic min/max price for slider (rounded to nearest 1000)
        $rawMin = Product::active()->min('price');
        $rawMax = Product::active()->max('price');
        $minPrice = $rawMin !== null ? (int) floor($rawMin / 1000) * 1000 : 0;
        $maxPrice = $rawMax !== null ? (int) ceil($rawMax / 1000) * 1000 : 50000;

        // Ensure min/max have at least 500 gap
        if ($maxPrice - $minPrice < 500) {
            $minPrice = max(0, $minPrice - 500);
            $maxPrice = $maxPrice + 500;
        }

        // AJAX: return JSON with rendered HTML
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            $gridHtml = view('catalog._products_grid', compact('products', 'wishlistIds'))->render();
            $paginationHtml = $products->appends($request->query())->links()->render();
            $hasFilters = $request->hasAny(['search', 'category', 'price_min', 'price_max']);
            $resultsInfo = $hasFilters
                ? '<p class="text-sm text-gray-500 mb-4">'.$products->total().' resultado(s) encontrado(s)</p>'
                : '';

            return response()->json([
                'html' => $gridHtml,
                'pagination' => $paginationHtml,
                'results_info' => $resultsInfo,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
            ]);
        }

        return view('catalog.index', compact('products', 'categories', 'wishlistIds', 'minPrice', 'maxPrice'));
    }

    public function show(Product $product): View
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load('category:id,name');

        $related = $product->category_id
            ? Product::query()
                ->where('is_active', true)
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->select(['id', 'name', 'description', 'price', 'image', 'category_id'])
                ->with('category:id,name')
                ->take(4)
                ->get()
            : collect();

        $inWishlist = auth()->check()
            ? auth()->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        return view('catalog.show', compact('product', 'related', 'inWishlist'));
    }
}
