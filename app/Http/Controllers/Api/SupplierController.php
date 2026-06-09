<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar supplier milik user yang sedang login.
     * Mendukung filter opsional berdasarkan category_id via query parameter.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Supplier::with('category')
            ->where('user_id', $request->user()->id)
            ->orderBy('id');

        // Filter berdasarkan kategori jika parameter diberikan
        if ($request->has('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data['user_id'] = $request->user()->id;
        $supplier = Supplier::create($data);
        $supplier->load('category');

        return response()->json($supplier, 201);
    }

    public function show(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $supplier->load('category');
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $supplier->update($data);
        $supplier->load('category');

        return response()->json($supplier);
    }

    public function destroy(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $supplier->delete();

        return response()->json(null, 204);
    }
}
