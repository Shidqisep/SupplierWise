<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $criterias = Criteria::where('user_id', $request->user()->id)
            ->orderBy('id')
            ->get();

        return response()->json($criterias);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'criteria_name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0',
        ]);

        $data['user_id'] = $request->user()->id;
        $criteria = Criteria::create($data);

        return response()->json($criteria, 201);
    }

    public function show(Request $request, Criteria $criterion): JsonResponse
    {
        if ($criterion->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($criterion);
    }

    public function update(Request $request, Criteria $criterion): JsonResponse
    {
        if ($criterion->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'criteria_name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0',
        ]);

        $criterion->update($data);

        return response()->json($criterion);
    }

    public function destroy(Request $request, Criteria $criterion): JsonResponse
    {
        if ($criterion->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $criterion->delete();

        return response()->json(null, 204);
    }
}
