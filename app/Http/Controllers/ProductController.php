<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($request->query('category'), fn ($query, string $category) => $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', $category)))
            ->when($request->query('search'), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return ApiResponder::success(ProductResource::collection($products), 'Products retrieved');
    }

    public function show(Product $product): JsonResponse
    {
        return ApiResponder::success(new ProductResource($product->load('category')), 'Product retrieved');
    }
}
