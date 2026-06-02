<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::orderBy('category_name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(null, 204);
    }
}
