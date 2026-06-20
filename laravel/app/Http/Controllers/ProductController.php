<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variations']);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->has('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest' => $query->orderByDesc('created_at'),
                default => $query->orderBy('name'),
            };
        } else {
            $query->orderBy('name');
        }

        $perPage = $request->get('per_page', 12);
        return response()->json($query->paginate($perPage));
    }

    public function show(string $id)
    {
        $product = Product::with(['category', 'images', 'variations'])->findOrFail($id);
        return response()->json($product);
    }
}
