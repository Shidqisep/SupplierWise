<div class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-[1fr_320px]">
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-slate-900">Criteria list</h2>
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-slate-700">Name</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Type</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Weight</th>
                            <th class="px-4 py-3 font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($criterias as $criteria)
                            <tr>
                                <td class="px-4 py-3 text-slate-900">{{ $criteria->criteria_name }}</td>
                                <td class="px-4 py-3 text-slate-700 capitalize">{{ $criteria->type }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $criteria->weight }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    <button wire:click="editCriteria({{ $criteria->id }})" class="rounded-full bg-slate-900 px-3 py-1 text-sm font-semibold text-white hover:bg-slate-700">
                                        Edit
                                    </button>
                                    <button wire:click="deleteCriteria({{ $criteria->id }})" class="ml-2 rounded-full bg-rose-500 px-3 py-1 text-sm font-semibold text-white hover:bg-rose-600">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center text-slate-500" colspan="4">No criteria found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">{{ $editingId ? 'Edit criteria' : 'Create criteria' }}</h2>

            @if (session()->has('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Criteria name</label>
                    <input wire:model.defer="criteria_name" type="text" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100" />
                    @error('criteria_name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Type</label>
                    <select wire:model.defer="type" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                    @error('type') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Weight</label>
                    <input wire:model.defer="weight" type="number" step="0.01" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100" />
                    @error('weight') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap gap-3">
                    <button wire:click.prevent="saveCriteria" class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                        {{ $editingId ? 'Update criteria' : 'Save criteria' }}
                    </button>
                    <button wire:click.prevent="resetForm" type="button" class="rounded-2xl bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
