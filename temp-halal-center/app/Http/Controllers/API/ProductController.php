<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HalalProductResource;
use App\Models\HalalProduct;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = HalalProduct::query()
            ->with('region')
            ->where('status', 'published')
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')->toString()))
            ->when($request->filled('brand_name'), fn ($query) => $query->where('brand_name', 'like', '%'.$request->string('brand_name')->toString().'%'))
            ->paginate(12);

        return ApiResponse::success(HalalProductResource::collection($products), 'Products loaded.');
    }
}
