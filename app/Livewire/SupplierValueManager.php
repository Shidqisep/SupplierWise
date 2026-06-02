<?php

namespace App\Livewire;

use App\Models\Criteria;
use App\Models\Supplier;
use App\Models\Supplier_Value;
use Illuminate\Support\Collection;
use Livewire\Component;

class SupplierValueManager extends Component
{
    public int $supplierId;
    public ?Supplier $supplier = null;

    /** @var array<int, float|string> keyed by criteria_id */
    public array $scores = [];

    public function mount(int $supplierId): void
    {
        $this->supplierId = $supplierId;
        $this->supplier = Supplier::findOrFail($supplierId);
        $this->loadScores();
    }

    public function loadScores(): void
    {
        $criterias = Criteria::orderBy('id')->get();
        $existingValues = Supplier_Value::where('id_supplier', $this->supplierId)->get()->keyBy('id_criteria');

        $this->scores = [];
        foreach ($criterias as $criteria) {
            $this->scores[$criteria->id] = $existingValues->has($criteria->id)
                ? (float) $existingValues->get($criteria->id)->score
                : '';
        }
    }

    public function saveScores(): void
    {
        $criterias = Criteria::orderBy('id')->get();

        foreach ($criterias as $criteria) {
            $score = $this->scores[$criteria->id] ?? '';

            if ($score === '' || $score === null) {
                // Remove existing value if score is cleared
                Supplier_Value::where('id_supplier', $this->supplierId)
                    ->where('id_criteria', $criteria->id)
                    ->delete();
                continue;
            }

            $scoreVal = (float) $score;

            Supplier_Value::updateOrCreate(
                [
                    'id_supplier' => $this->supplierId,
                    'id_criteria' => $criteria->id,
                ],
                [
                    'score' => $scoreVal,
                ]
            );
        }

        session()->flash('success', 'Nilai supplier berhasil disimpan.');
        $this->loadScores();
    }

    public function render()
    {
        $criterias = Criteria::orderBy('id')->get();
        $filledCount = collect($this->scores)->filter(fn($v) => $v !== '' && $v !== null)->count();

        return view('livewire.supplier-value-manager', [
            'criterias' => $criterias,
            'filledCount' => $filledCount,
        ]);
    }
}
