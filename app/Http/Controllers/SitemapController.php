<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $products = Product::active()->get();
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Landing
        $xml .= "<url><loc>{$baseUrl}/</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>";

        // Catalog
        $xml .= "<url><loc>{$baseUrl}/productos</loc><changefreq>daily</changefreq><priority>0.9</priority></url>";

        // Products
        foreach ($products as $product) {
            $loc = route('catalog.show', $product, false);
            $xml .= "<url><loc>{$loc}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
