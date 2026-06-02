<div>
    <div class="p-lg max-w-5xl mx-auto space-y-lg">

        {{-- Back button + Title --}}
        <section class="space-y-md">
            <a href="{{ route('suppliers') }}"
                class="inline-flex items-center gap-xs text-secondary hover:text-primary font-label-md text-label-md transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Daftar Supplier
            </a>
            <div class="flex justify-between items-end flex-wrap gap-md">
                <div>
                    <h2 class="font-display-lg text-display-lg text-on-surface">Nilai Kriteria</h2>
                    <p class="font-body-lg text-body-lg text-secondary mt-xs">
                        Kelola nilai setiap kriteria untuk
                        <span class="font-semibold text-primary">{{ $supplier->supplier_name }}</span>
                    </p>
                </div>
            </div>
        </section>

        {{-- Supplier Info Card --}}
        <section class="relative overflow-hidden rounded-lg bg-primary p-xl text-on-primary shadow-xl">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-white/10 to-transparent pointer-events-none"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-container opacity-20 blur-3xl rounded-full"></div>
            <div class="relative z-10 flex items-center gap-xl">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <div class="flex-grow">
                    <h3 class="font-headline-md text-headline-md font-bold">{{ $supplier->supplier_name }}</h3>
                    <div class="flex items-center gap-lg mt-xs font-body-md text-body-md text-primary-fixed-dim">
                        @if($supplier->address)
                            <span class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[16px]">location_on</span>
                                {{ $supplier->address }}
                            </span>
                        @endif
                        @if($supplier->contact)
                            <span class="flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[16px]">call</span>
                                {{ $supplier->contact }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-center flex-shrink-0">
                    <div class="font-display-lg text-display-lg font-black">{{ $filledCount }}/{{ $criterias->count() }}</div>
                    <div class="font-label-md text-label-md uppercase opacity-80">Terisi</div>
                </div>
            </div>
        </section>

        {{-- Flash Message --}}
        @if(session()->has('success'))
            <div class="flex items-center gap-md p-md bg-primary-container/10 border border-primary-container/30 rounded-lg text-primary-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span class="font-body-md text-body-md font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Criteria Score Form --}}
        <form wire:submit="saveScores">
            <section class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h4 class="font-headline-sm text-headline-sm text-on-surface">Input Nilai Kriteria</h4>
                    <span class="font-label-md text-label-md text-secondary">
                        {{ $filledCount }} dari {{ $criterias->count() }} kriteria terisi
                    </span>
                </div>

                @if($criterias->isEmpty())
                    <div class="p-xl text-center text-secondary">
                        <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-md">
                            <span class="material-symbols-outlined text-3xl text-secondary">rule</span>
                        </div>
                        <p class="font-body-md text-body-md">Belum ada kriteria. Tambahkan kriteria terlebih dahulu.</p>
                        <a href="{{ route('criteria') }}"
                            class="inline-flex items-center gap-xs mt-md text-primary font-label-md text-label-md hover:underline">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Kelola Kriteria
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-outline-variant/30">
                        @foreach($criterias as $criteria)
                            <div class="px-lg py-md flex items-center gap-lg hover:bg-surface-container-low transition-colors">
                                {{-- Criteria indicator --}}
                                <div class="flex-shrink-0 w-10 h-10 {{ $criteria->type === 'benefit' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container' }} rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">
                                        {{ $criteria->type === 'benefit' ? 'trending_up' : 'trending_down' }}
                                    </span>
                                </div>

                                {{-- Criteria info --}}
                                <div class="flex-grow min-w-0">
                                    <div class="font-body-lg text-body-lg font-semibold text-on-surface">
                                        {{ $criteria->criteria_name }}
                                    </div>
                                    <div class="flex items-center gap-md mt-xs">
                                        <span class="px-sm py-[2px] {{ $criteria->type === 'benefit' ? 'bg-primary-container/10 text-primary-container' : 'bg-error-container/50 text-error' }} rounded-full text-[10px] font-black uppercase">
                                            {{ $criteria->type }}
                                        </span>
                                        <span class="font-label-md text-label-md text-secondary">
                                            Bobot: {{ round($criteria->weight * 100) }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- Score input --}}
                                <div class="flex-shrink-0 w-36">
                                    <input
                                        type="number"
                                        step="any"
                                        wire:model="scores.{{ $criteria->id }}"
                                        placeholder="0"
                                        class="w-full text-center px-md py-sm bg-surface-container-low border border-outline-variant rounded-DEFAULT font-body-lg text-body-lg text-on-surface font-semibold focus:ring-2 focus:ring-primary focus:border-primary transition-all [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Submit Button --}}
                    <div class="px-lg py-md border-t border-outline-variant flex justify-end gap-md">
                        <a href="{{ route('suppliers') }}"
                            class="flex items-center gap-xs px-lg py-sm rounded-DEFAULT border border-outline text-on-surface font-label-md text-label-md hover:bg-surface-container-low transition-all">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex items-center gap-xs px-lg py-sm rounded-DEFAULT bg-primary text-on-primary font-label-md text-label-md hover:opacity-90 active:scale-95 transition-all shadow-md">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Nilai
                        </button>
                    </div>
                @endif
            </section>
        </form>

        {{-- Spacer --}}
        <div class="h-12"></div>
    </div>
</div>
