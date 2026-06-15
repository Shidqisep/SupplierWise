<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\Supplier_Value;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Menghitung ranking supplier menggunakan metode COPRAS.
     *
     * Query Parameters:
     * - category_id (required): ID kategori supplier yang akan dievaluasi
     * - search (optional): Filter nama supplier berdasarkan keyword
     *
     * Hanya memproses data milik user yang sedang login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
                // Pastikan kategori ini milik user yang sedang login
                function ($attribute, $value, $fail) use ($userId) {
                    $category = Category::where('id', $value)
                        ->where('user_id', $userId)
                        ->first();
                    if (! $category) {
                        $fail('Kategori tidak ditemukan atau bukan milik Anda.');
                    }
                },
            ],
            'search' => 'nullable|string|max:255',
        ]);

        $categoryId = $request->query('category_id');
        $search = $request->query('search', '');

        // Hanya ambil data milik user yang sedang login
        $suppliers = Supplier::where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->orderBy('id')
            ->get();

        $criterias = Criteria::where('user_id', $userId)
            ->orderBy('id')
            ->get();

        $supplierValues = Supplier_Value::whereIn('id_supplier', $suppliers->pluck('id'))->get();

        // Jika data tidak lengkap, kembalikan response kosong
        if ($suppliers->isEmpty() || $criterias->isEmpty() || $supplierValues->isEmpty()) {
            return response()->json([
                'category' => Category::where('id', $categoryId)->where('user_id', $userId)->first(),
                'rankings' => [],
                'criterias' => $criterias,
                'supplier_count' => $suppliers->count(),
                'total_weight' => $criterias->sum('weight'),
            ]);
        }

        // --- Langkah 1: Membentuk matriks keputusan X[i][j] ---
        $matrix = [];
        foreach ($supplierValues as $sv) {
            $matrix[$sv->id_supplier][$sv->id_criteria] = $sv->score;
        }

        // --- Langkah 2: Normalisasi per kolom (sum-based) ---
        $colSums = [];
        foreach ($criterias as $criteria) {
            $sum = 0;
            foreach ($suppliers as $supplier) {
                $sum += $matrix[$supplier->id][$criteria->id] ?? 0;
            }
            $colSums[$criteria->id] = $sum;
        }

        $normalized = [];
        foreach ($suppliers as $supplier) {
            foreach ($criterias as $criteria) {
                $val = $matrix[$supplier->id][$criteria->id] ?? 0;
                $normalized[$supplier->id][$criteria->id] =
                    $colSums[$criteria->id] > 0 ? $val / $colSums[$criteria->id] : 0;
            }
        }

        // --- Langkah 3: Matriks bobot ternormalisasi ---
        $totalWeight = $criterias->sum('weight');
        $weighted = [];
        foreach ($suppliers as $supplier) {
            foreach ($criterias as $criteria) {
                $wNorm = $totalWeight > 0 ? $criteria->weight / $totalWeight : 0;
                $weighted[$supplier->id][$criteria->id] =
                    $normalized[$supplier->id][$criteria->id] * $wNorm;
            }
        }

        // --- Langkah 4 & 5: Menghitung S+ dan S- ---
        $sPlus = [];
        $sMinus = [];
        foreach ($suppliers as $supplier) {
            $sp = 0;
            $sm = 0;
            foreach ($criterias as $criteria) {
                $d = $weighted[$supplier->id][$criteria->id];
                if ($criteria->type === 'benefit') {
                    $sp += $d;
                } else {
                    $sm += $d;
                }
            }
            $sPlus[$supplier->id] = $sp;
            $sMinus[$supplier->id] = $sm;
        }

        // --- Langkah 6: Signifikansi relatif Q ---
        $sumSMinus = array_sum($sMinus);
        $sumInvSMinus = 0;
        foreach ($sMinus as $sm) {
            if ($sm > 0) {
                $sumInvSMinus += 1 / $sm;
            }
        }

        $q = [];
        foreach ($suppliers as $supplier) {
            $sid = $supplier->id;
            if ($sMinus[$sid] > 0 && $sumInvSMinus > 0) {
                $q[$sid] = $sPlus[$sid] + ($sumSMinus / ($sMinus[$sid] * $sumInvSMinus));
            } else {
                // Tidak ada kriteria cost atau supplier ini memiliki skor cost 0
                $q[$sid] = $sPlus[$sid];
            }
        }

        // --- Langkah 7: Derajat kemanfaatan U (persentase) ---
        $qMax = max($q) ?: 1;
        $utility = [];
        foreach ($q as $sid => $qVal) {
            $utility[$sid] = ($qVal / $qMax) * 100;
        }

        // --- Membangun hasil ranking ---
        $rankings = [];
        foreach ($suppliers as $supplier) {
            $sid = $supplier->id;
            $criteriaScores = [];
            foreach ($criterias as $criteria) {
                $criteriaScores[] = [
                    'criteria_id' => $criteria->id,
                    'criteria_name' => $criteria->criteria_name,
                    'type' => $criteria->type,
                    'raw' => $matrix[$sid][$criteria->id] ?? 0,
                    'normalized' => round($normalized[$sid][$criteria->id], 4),
                    'weighted' => round($weighted[$sid][$criteria->id], 4),
                ];
            }

            $rankings[] = [
                'supplier' => $supplier->load('category'),
                's_plus' => round($sPlus[$sid], 4),
                's_minus' => round($sMinus[$sid], 4),
                'q' => round($q[$sid], 4),
                'utility' => round($utility[$sid], 2),
                'criteria_scores' => $criteriaScores,
            ];
        }

        // Urutkan berdasarkan utility (descending)
        usort($rankings, fn($a, $b) => $b['utility'] <=> $a['utility']);

        // Berikan nomor ranking
        foreach ($rankings as $i => &$r) {
            $r['rank'] = $i + 1;
        }

        // Filter pencarian berdasarkan nama supplier (opsional)
        if ($search !== '') {
            $search = strtolower($search);
            $rankings = array_values(array_filter($rankings, function ($r) use ($search) {
                return str_contains(strtolower($r['supplier']->supplier_name), $search)
                    || str_contains(strtolower($r['supplier']->address ?? ''), $search);
            }));
        }

        return response()->json([
            'category' => Category::where('id', $categoryId)->where('user_id', $userId)->first(),
            'rankings' => $rankings,
            'criterias' => $criterias,
            'supplier_count' => $suppliers->count(),
            'total_weight' => $totalWeight,
        ]);
    }
}
