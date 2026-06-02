<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Criteria::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'criteria_name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0',
        ]);

        $criteria = Criteria::create($data);

        return response()->json($criteria, 201);
    }

    public function show(Criteria $criterion): JsonResponse
    {
        return response()->json($criterion);
    }

    public function update(Request $request, Criteria $criterion): JsonResponse
    {
        $data = $request->validate([
            'criteria_name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0',
        ]);

        $criterion->update($data);

        return response()->json($criterion);
    }

    public function destroy(Criteria $criterion): JsonResponse
    {
        $criterion->delete();

        return response()->json(null, 204);
    }
}
