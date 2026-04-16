<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $this->storeProductImage($request->file('image'), $validated);
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $product->load('category');
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $this->storeProductImage($request->file('image'), $validated);
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    private function storeProductImage($file, array $validated): string
    {
        // Validación adicional de seguridad (defensa en profundidad)
        $allowedMimes = ['jpeg', 'png', 'jpg', 'gif', 'webp', 'avif'];
        $extension = strtolower($file->extension());

        if (! in_array($extension, $allowedMimes)) {
            abort(422, 'Tipo de archivo no permitido.');
        }

        // Verificar que el MIME real coincida con la extensión
        $realMime = $file->getMimeType();
        $allowedMimetypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];

        if (! in_array($realMime, $allowedMimetypes)) {
            abort(422, 'El archivo no es una imagen válida.');
        }

        // Validar tamaño máximo (5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            abort(422, 'La imagen no puede exceder 5MB.');
        }

        // Generar nombre seguro (siempre .webp)
        $categoryName = Category::find($validated['category_id'] ?? null)?->name ?? 'sin-categoria';
        $productName = Str::slug($validated['name']);
        $uniqueId = Str::uuid();
        $filename = Str::slug($categoryName)."-{$productName}-{$uniqueId}.webp";

        // Procesar imagen: comprimir, redimensionar, convertir a WebP
        $manager = new ImageManager(new Driver);
        $image = $manager->decode($file);

        // Redimensionar solo si es más grande que 1920px (mantener proporción)
        $image->scaleDown(width: 1920);

        // Guardar como WebP con 85% calidad
        $storagePath = Storage::disk('public')->path('products');
        if (! is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $image->save(Storage::disk('public')->path("products/{$filename}"), quality: 85);

        return "products/{$filename}";
    }
}
