<?php

namespace App\Livewire;

use App\Models\Criteria;
use Illuminate\Support\Collection;
use Livewire\Component;

class CriteriaManager extends Component
{
    public Collection $criterias;
    public ?int $editingId = null;
    public string $criteria_name = '';
    public string $type = 'benefit';
    public float $weight = 0;

    public function mount(): void
    {
        $this->loadCriterias();
    }

    public function loadCriterias(): void
    {
        $this->criterias = Criteria::orderBy('id')->get();
    }

    public function rules(): array
    {
        return [
            'criteria_name' => 'required|string|max:255',
            'type' => 'required|in:benefit,cost',
            'weight' => 'required|numeric|min:0',
        ];
    }

    public function saveCriteria(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $criteria = Criteria::findOrFail($this->editingId);
            $criteria->update($data);
            session()->flash('success', 'Criteria updated successfully.');
        } else {
            Criteria::create($data);
            session()->flash('success', 'Criteria created successfully.');
        }

        $this->resetForm();
        $this->loadCriterias();
    }

    public function editCriteria(int $criteriaId): void
    {
        $criteria = Criteria::findOrFail($criteriaId);

        $this->editingId = $criteria->id;
        $this->criteria_name = $criteria->criteria_name;
        $this->type = $criteria->type;
        $this->weight = (float) $criteria->weight;
    }

    public function deleteCriteria(int $criteriaId): void
    {
        Criteria::findOrFail($criteriaId)->delete();

        session()->flash('success', 'Criteria deleted successfully.');
        $this->resetForm();
        $this->loadCriterias();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->criteria_name = '';
        $this->type = 'benefit';
        $this->weight = 0;
    }

    public function render()
    {
        return view('livewire.criteria-manager');
    }
}
