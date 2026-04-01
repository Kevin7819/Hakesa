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
        $query = Product::active()->with('category');

        // Búsqueda por nombre
        if ($request->filled('search')) {
            // Escape LIKE wildcards to prevent injection
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
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
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('sort_order')->get();

        // AJAX: return JSON with rendered HTML
        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            $gridHtml = view('catalog._products_grid', compact('products'))->render();
            $paginationHtml = $products->appends($request->query())->links()->render();
            $hasFilters = $request->hasAny(['search', 'category', 'price_min', 'price_max']);
            $resultsInfo = $hasFilters
                ? '<p class="text-sm text-gray-500 mb-4">'.$products->total().' resultado(s) encontrado(s)</p>'
                : '';

            return response()->json([
                'html' => $gridHtml,
                'pagination' => $paginationHtml,
                'results_info' => $resultsInfo,
            ]);
        }

        return view('catalog.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load('category');

        $related = $product->category_id
            ? Product::active()->with('category')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get()
            : collect();

        return view('catalog.show', compact('product', 'related'));
    }
}
