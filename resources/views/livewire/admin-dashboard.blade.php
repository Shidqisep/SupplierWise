<div class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-500">Admin panel</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Supplier & Criteria Management</h1>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="setTab('suppliers')" type="button" class="rounded-full px-4 py-2 text-sm font-semibold transition 
                        {{ $tab === 'suppliers' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Suppliers
                    </button>
                    <button wire:click="setTab('criteria')" type="button" class="rounded-full px-4 py-2 text-sm font-semibold transition 
                        {{ $tab === 'criteria' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Criteria
                    </button>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            @if ($tab === 'suppliers')
                @livewire('supplier-manager')
            @else
                @livewire('criteria-manager')
            @endif
        </div>
    </div>
</div>
