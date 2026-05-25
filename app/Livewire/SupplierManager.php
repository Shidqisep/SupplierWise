<?php

namespace App\Livewire;

use App\Models\Supplier;
use Illuminate\Support\Collection;
use Livewire\Component;

class SupplierManager extends Component
{
    public Collection $suppliers;
    public ?int $editingId = null;
    public string $supplier_name = '';
    public ?string $contact = null;
    public ?string $address = null;

    public function mount(): void
    {
        $this->loadSuppliers();
    }

    public function loadSuppliers(): void
    {
        $this->suppliers = Supplier::orderBy('id')->get();
    }

    public function rules(): array
    {
        return [
            'supplier_name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function saveSupplier(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            $supplier = Supplier::findOrFail($this->editingId);
            $supplier->update($data);

            session()->flash('success', 'Supplier updated successfully.');
        } else {
            Supplier::create($data);
            session()->flash('success', 'Supplier created successfully.');
        }

        $this->resetForm();
        $this->loadSuppliers();
    }

    public function editSupplier(int $supplierId): void
    {
        $supplier = Supplier::findOrFail($supplierId);

        $this->editingId = $supplier->id;
        $this->supplier_name = $supplier->supplier_name;
        $this->contact = $supplier->contact;
        $this->address = $supplier->address;
    }

    public function deleteSupplier(int $supplierId): void
    {
        Supplier::findOrFail($supplierId)->delete();

        session()->flash('success', 'Supplier deleted successfully.');
        $this->resetForm();
        $this->loadSuppliers();
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->supplier_name = '';
        $this->contact = null;
        $this->address = null;
    }

    public function render()
    {
        return view('livewire.supplier-manager');
    }
}
