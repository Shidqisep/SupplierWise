<?php

namespace App\Livewire;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\Supplier_Value;
use Livewire\Component;

class ResultManager extends Component
{
    public string $search = '';
    public string $selectedCategory = '';

    public function mount()
    {
        $firstCategory = \App\Models\Category::where('user_id', auth()->id())
            ->orderBy('category_name')
            ->first();
        if ($firstCategory) {
            $this->selectedCategory = (string) $firstCategory->id;
        }
    }

    /**
     * COPRAS (COmplex PRoportional ASsessment) Method
     *
     * Steps:
     * 1. Build decision matrix X[i][j]
     * 2. Normalize: r_ij = x_ij / SUM(x_ij) per column
     * 3. Weighted normalize: d_ij = r_ij * w_j
     * 4. S+_i = sum of d_ij for benefit criteria
     * 5. S-_i = sum of d_ij for cost criteria
     * 6. Q_i  = S+_i + SUM(S-) / (S-_i * SUM(1/S-))
     *    If no cost criteria → Q_i = S+_i
     * 7. Utility degree: U_i = (Q_i / Q_max) * 100
     */
    public function calculateCOPRAS(): array
    {
        if ($this->selectedCategory === '') {
            return [
                'rankings'    => collect(),
                'criterias'   => collect(),
                'suppliers'   => collect(),
                'totalWeight' => 0,
            ];
        }

        $suppliers = Supplier::where('category_id', $this->selectedCategory)
            ->where('user_id', auth()->id())
            ->orderBy('id')
            ->get();
        $criterias = Criteria::where('user_id', auth()->id())
            ->orderBy('id')
            ->get();
        $supplierValues = Supplier_Value::whereIn('id_supplier', $suppliers->pluck('id'))->get();

        if ($suppliers->isEmpty() || $criterias->isEmpty() || $supplierValues->isEmpty()) {
            return [
                'rankings'    => collect(),
                'criterias'   => $criterias,
                'suppliers'   => $suppliers,
                'totalWeight' => $criterias->sum('weight'),
            ];
        }

        // --- Step 1: Build decision matrix ---
        $matrix = [];
        foreach ($supplierValues as $sv) {
            $matrix[$sv->id_supplier][$sv->id_criteria] = $sv->score;
        }

        // --- Step 2: Normalize per column (sum-based) ---
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

        // --- Step 3: Weighted normalized matrix ---
        $totalWeight = $criterias->sum('weight');
        $weighted = [];
        foreach ($suppliers as $supplier) {
            foreach ($criterias as $criteria) {
                $wNorm = $totalWeight > 0 ? $criteria->weight / $totalWeight : 0;
                $weighted[$supplier->id][$criteria->id] =
                    $normalized[$supplier->id][$criteria->id] * $wNorm;
            }
        }

        // --- Step 4 & 5: Calculate S+ and S- ---
        $sPlus  = [];
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
            $sPlus[$supplier->id]  = $sp;
            $sMinus[$supplier->id] = $sm;
        }

        // --- Step 6: Relative significance Q ---
        $sumSMinus    = array_sum($sMinus);
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
                // No cost criteria or this supplier has 0 cost score
                $q[$sid] = $sPlus[$sid];
            }
        }

        // --- Step 7: Utility degree U (percentage) ---
        $qMax = max($q) ?: 1;
        $utility = [];
        foreach ($q as $sid => $qVal) {
            $utility[$sid] = ($qVal / $qMax) * 100;
        }

        // --- Build rankings ---
        $rankings = [];
        foreach ($suppliers as $supplier) {
            $sid = $supplier->id;
            $criteriaScores = [];
            foreach ($criterias as $criteria) {
                $criteriaScores[$criteria->id] = [
                    'raw'        => $matrix[$sid][$criteria->id] ?? 0,
                    'normalized' => round($normalized[$sid][$criteria->id], 4),
                    'weighted'   => round($weighted[$sid][$criteria->id], 4),
                ];
            }

            $rankings[] = [
                'supplier'        => $supplier,
                'sPlus'           => round($sPlus[$sid], 4),
                'sMinus'          => round($sMinus[$sid], 4),
                'q'               => round($q[$sid], 4),
                'utility'         => round($utility[$sid], 2),
                'criteria_scores' => $criteriaScores,
            ];
        }

        // Sort by utility descending
        usort($rankings, fn($a, $b) => $b['utility'] <=> $a['utility']);

        // Assign ranks
        foreach ($rankings as $i => &$r) {
            $r['rank'] = $i + 1;
        }

        return [
            'rankings'    => collect($rankings),
            'criterias'   => $criterias,
            'suppliers'   => $suppliers,
            'totalWeight' => $totalWeight,
        ];
    }

    public function render()
    {
        $result   = $this->calculateCOPRAS();
        $rankings = $result['rankings'];

        // Apply search filter
        if ($this->search !== '') {
            $search   = strtolower($this->search);
            $rankings = $rankings->filter(function ($r) use ($search) {
                return str_contains(strtolower($r['supplier']->supplier_name), $search)
                    || str_contains(strtolower($r['supplier']->address ?? ''), $search);
            })->values();
        }

        return view('livewire.result-manager', [
            'rankings'      => $rankings,
            'criterias'     => $result['criterias'],
            'supplierCount' => $result['suppliers']->count(),
            'totalWeight'   => $result['totalWeight'],
            'categoriesList' => \App\Models\Category::where('user_id', auth()->id())->orderBy('category_name')->get(),
        ]);
    }
}