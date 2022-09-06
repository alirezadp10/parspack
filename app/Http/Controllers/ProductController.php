<?php

namespace App\Http\Controllers;

use App\Http\Filters\ProductFilters;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  ProductFilters  $filters
     * @return JsonResponse
     */
    public function index(ProductFilters $filters): JsonResponse
    {
        $products = Cache::remember('questions', config('cache.time.index'), function () use ($filters) {
            return Product::with('comments')->filter($filters)->paginate();
        });

        return response()->json(ProductResource::collection($products)->response()->getData(true), Response::HTTP_OK);
    }
}
