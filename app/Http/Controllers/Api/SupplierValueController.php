<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier_Value;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierValueController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Supplier_Value::with(['supplier', 'criteria'])->orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'id_criteria' => 'required|exists:criterias,id',
            'score' => 'required|numeric',
        ]);

        $value = Supplier_Value::create($data);

        return response()->json($value, 201);
    }

    public function show(Supplier_Value $supplierValue): JsonResponse
    {
        return response()->json($supplierValue->load(['supplier', 'criteria']));
    }

    public function update(Request $request, Supplier_Value $supplierValue): JsonResponse
    {
        $data = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'id_criteria' => 'required|exists:criterias,id',
            'score' => 'required|numeric',
        ]);

        $supplierValue->update($data);

        return response()->json($supplierValue);
    }

    public function destroy(Supplier_Value $supplierValue): JsonResponse
    {
        $supplierValue->delete();

        return response()->json(null, 204);
    }
}
