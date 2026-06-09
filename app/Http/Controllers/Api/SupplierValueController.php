<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\Supplier_Value;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierValueController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Hanya tampilkan nilai supplier yang dimiliki oleh user yang sedang login
        $userSupplierIds = Supplier::where('user_id', $request->user()->id)->pluck('id');

        return response()->json(
            Supplier_Value::with(['supplier', 'criteria'])
                ->whereIn('id_supplier', $userSupplierIds)
                ->orderBy('id')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'id_criteria' => 'required|exists:criterias,id',
            'score' => 'required|numeric',
        ]);

        // Pastikan supplier dan criteria dimiliki oleh user yang sedang login
        $supplier = Supplier::findOrFail($data['id_supplier']);
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Supplier tidak dimiliki oleh Anda'], 403);
        }

        $criteria = Criteria::findOrFail($data['id_criteria']);
        if ($criteria->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Kriteria tidak dimiliki oleh Anda'], 403);
        }

        $value = Supplier_Value::create($data);

        return response()->json($value, 201);
    }

    public function show(Request $request, Supplier_Value $supplierValue): JsonResponse
    {
        $supplier = Supplier::findOrFail($supplierValue->id_supplier);
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($supplierValue->load(['supplier', 'criteria']));
    }

    public function update(Request $request, Supplier_Value $supplierValue): JsonResponse
    {
        $supplier = Supplier::findOrFail($supplierValue->id_supplier);
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'id_criteria' => 'required|exists:criterias,id',
            'score' => 'required|numeric',
        ]);

        $supplierValue->update($data);

        return response()->json($supplierValue);
    }

    public function destroy(Request $request, Supplier_Value $supplierValue): JsonResponse
    {
        $supplier = Supplier::findOrFail($supplierValue->id_supplier);
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $supplierValue->delete();

        return response()->json(null, 204);
    }
}
