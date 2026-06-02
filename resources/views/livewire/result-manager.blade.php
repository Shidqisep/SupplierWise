<div id="tab-result" class="tab-page">
    {{-- Single root element to prevent multi-root error --}}

    <div class="p-lg max-w-6xl mx-auto space-y-lg">
        <!-- Hero Title Section -->
        <section class="flex justify-between items-end flex-wrap gap-md">
            <div>
                <h2 class="font-display-lg text-display-lg text-on-surface">Hasil Keputusan (COPRAS)</h2>
                <p class="font-body-lg text-body-lg text-secondary mt-xs">
                    Analisis peringkat supplier menggunakan metode COmplex PRoportional ASsessment.
                </p>
            </div>
            <div class="flex items-center gap-md">
                <div class="relative group">
                    <div class="absolute left-md top-1/2 -translate-y-1/2 text-primary flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                    </div>
                    <select wire:model.live="selectedCategory" class="pl-[40px] pr-[36px] py-sm bg-surface-container-lowest border border-outline-variant rounded-DEFAULT text-sm font-label-md text-on-surface focus:ring-2 focus:ring-primary focus:border-primary appearance-none cursor-pointer shadow-sm hover:border-primary transition-all duration-200 min-w-[220px] outline-none">
                        @if($categoriesList->isEmpty())
                            <option value="">Belum ada kategori</option>
                        @else
                            @foreach($categoriesList as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute right-sm top-1/2 -translate-y-1/2 text-secondary flex items-center pointer-events-none group-hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]">expand_more</span>
                    </div>
                </div>
                <button wire:click="$refresh"
                    class="flex items-center gap-xs px-md py-sm rounded-DEFAULT border border-outline text-on-surface font-label-md text-label-md hover:bg-surface-container-low transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]" data-icon="refresh">refresh</span>
                    Hitung Ulang
                </button>
            </div>
        </section>

        @if($rankings->isNotEmpty())
            {{-- ===== Featured Recommendation Card (#1) ===== --}}
            @php $top = $rankings->first(); @endphp
            <section class="relative overflow-hidden rounded-lg bg-primary p-xl text-on-primary shadow-xl">
                <!-- Abstract Background Ornament -->
                <div
                    class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-white/10 to-transparent pointer-events-none">
                </div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-container opacity-20 blur-3xl rounded-full">
                </div>
                <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-xl">
                    <div class="space-y-md">
                        <div
                            class="inline-flex items-center gap-xs px-sm py-xs bg-white/20 backdrop-blur-md rounded-full border border-white/20">
                            <span class="material-symbols-outlined text-[16px]" data-icon="verified"
                                style="font-variation-settings: 'FILL' 1;">verified</span>
                            <span class="font-label-md text-label-md tracking-widest uppercase">Rekomendasi Utama</span>
                        </div>
                        <div class="space-y-xs">
                            <h3 class="font-display-lg text-display-lg font-black leading-tight">
                                {{ $top['supplier']->supplier_name }}
                            </h3>
                            <p class="font-body-lg text-body-lg text-primary-fixed-dim">
                                {{ $top['supplier']->address ?? 'Alamat belum diisi' }}
                                @if($top['supplier']->contact)
                                    • {{ $top['supplier']->contact }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-xl py-md">
                            <div class="text-center">
                                <div class="font-display-lg text-display-lg font-black">{{ $top['utility'] }}%</div>
                                <div class="font-label-md text-label-md uppercase opacity-80">Utilitas</div>
                            </div>
                            <div class="h-12 w-[1px] bg-white/20"></div>
                            <div class="flex-1 space-y-sm">
                                @foreach($criterias->take(2) as $crit)
                                    @php
                                        $critScore = $top['criteria_scores'][$crit->id] ?? null;
                                        $pct = $critScore ? round($critScore['weighted'] * 100 * 4, 0) : 0;
                                        $pct = min($pct, 100);
                                    @endphp
                                    <div class="space-y-xs">
                                        <div class="flex justify-between font-label-md text-label-md">
                                            <span>{{ $crit->criteria_name }}</span>
                                            <span>{{ $pct }}%</span>
                                        </div>
                                        <div class="h-2 w-full bg-white/20 rounded-full overflow-hidden">
                                            <div class="h-full bg-primary-fixed rounded-full transition-all duration-500"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <div
                            class="w-full h-48 bg-white/10 rounded-DEFAULT glass-card p-md flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="font-label-md text-label-md uppercase font-bold">Detail Skor Kriteria</span>
                                <span class="text-xs text-primary-fixed-dim">COPRAS Method</span>
                            </div>
                            <!-- Mini bar chart from actual criteria scores -->
                            <div class="flex items-end gap-xs h-24">
                                @foreach($criterias as $crit)
                                    @php
                                        $critScore = $top['criteria_scores'][$crit->id] ?? null;
                                        $heightPct = $critScore ? round($critScore['normalized'] * 100) : 0;
                                    @endphp
                                    <div class="flex-1 bg-white/40 rounded-t-sm transition-all duration-500 hover:bg-white/60"
                                        style="height: {{ max($heightPct, 5) }}%"
                                        title="{{ $crit->criteria_name }}: {{ $heightPct }}%"></div>
                                @endforeach
                            </div>
                            <p class="font-body-md text-body-md text-white/80">
                                Peringkat <span class="text-white font-bold">#1</span> dari
                                <span class="text-white font-bold">{{ $supplierCount }}</span> supplier
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Bento Layout for Stats & Comparison ===== --}}
            <div class="bento-grid">
                <!-- Visual Comparison Chart -->
                <div
                    class="col-span-8 bg-surface-container-lowest mb-8 p-lg rounded-lg border border-outline-variant shadow-[0_4px_20px_rgba(15,23,42,0.04)] space-y-md">
                    <div class="flex justify-between items-center">
                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Perbandingan Visual</h4>
                    </div>
                    <div class="space-y-md pt-md">
                        @foreach($rankings->take(5) as $idx => $item)
                            @php
                                $barWidth = round($item['utility']);
                            @endphp
                            <div class="space-y-xs">
                                <div class="flex justify-between font-label-md text-label-md">
                                    <span class="text-on-surface {{ $idx === 0 ? 'font-bold' : '' }}">
                                        {{ $item['supplier']->supplier_name }}
                                    </span>
                                    <span class="{{ $idx === 0 ? 'text-primary font-bold' : 'text-secondary' }}">
                                        {{ $item['utility'] }}%
                                    </span>
                                </div>
                                <div class="h-3 w-full bg-surface-container rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-700 {{ $idx === 0 ? 'bg-primary' : 'bg-secondary-fixed-dim' }}"
                                        style="width: {{ $barWidth }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Secondary Stat Cards -->
                <div class="col-span-4 flex flex-col gap-md">
                    <div
                        class="flex-1 bg-surface-container-lowest p-md rounded-lg border border-outline-variant flex items-center gap-md">
                        <div
                            class="w-12 h-12 bg-primary-container/10 rounded-full flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined" data-icon="groups">groups</span>
                        </div>
                        <div>
                            <div class="font-label-md text-label-md text-secondary">Total Supplier</div>
                            <div class="font-headline-sm text-headline-sm">{{ $supplierCount }} Perusahaan</div>
                        </div>
                    </div>
                    <div
                        class="flex-1 bg-surface-container-lowest p-md rounded-lg border border-outline-variant flex items-center gap-md">
                        <div
                            class="w-12 h-12 bg-tertiary-container/10 rounded-full flex items-center justify-center text-tertiary">
                            <span class="material-symbols-outlined" data-icon="balance">balance</span>
                        </div>
                        <div>
                            <div class="font-label-md text-label-md text-secondary">Total Kriteria</div>
                            <div class="font-headline-sm text-headline-sm">{{ $criterias->count() }} Parameter</div>
                        </div>
                    </div>
                    <div
                        class="flex-1 bg-tertiary-container p-md rounded-lg text-on-tertiary shadow-lg flex items-center gap-md">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined" data-icon="lightbulb"
                                style="font-variation-settings: 'FILL' 1;">lightbulb</span>
                        </div>
                        <div>
                            <div class="font-label-md text-label-md text-tertiary-fixed">Total Bobot</div>
                            <div class="font-body-md text-body-md font-bold">
                                {{ round($totalWeight, 2) }}
                                @if(abs($totalWeight - 1.0) < 0.001 || abs($totalWeight - 100) < 0.1)
                                    <span class="text-xs opacity-80">✓ Valid</span>
                                @else
                                    <span class="text-xs opacity-80">⚠ Periksa bobot</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Detailed Rankings Table ===== --}}
            <section
                class="bg-surface-container-lowest rounded-lg border border-outline-variant shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
                    <h4 class="font-headline-sm text-headline-sm text-on-surface">Daftar Lengkap Peringkat</h4>
                    <div class="flex items-center gap-sm">
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-secondary text-[20px]"
                                data-icon="search">search</span>
                            <input wire:model.live.debounce.300ms="search"
                                class="pl-xl pr-md py-xs bg-surface-container-low border-none rounded-full text-sm w-64 focus:ring-1 focus:ring-primary"
                                placeholder="Cari Supplier..." type="text" />
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low">
                            <tr>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary">RANK</th>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary">SUPPLIER</th>
                                @foreach($criterias as $crit)
                                    <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">
                                        {{ strtoupper($crit->criteria_name) }}
                                        <div class="text-[10px] font-normal opacity-70">
                                            {{ $crit->type === 'benefit' ? '▲ Benefit' : '▼ Cost' }}
                                        </div>
                                    </th>
                                @endforeach
                                <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">S+</th>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">S−</th>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">Q</th>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">UTILITAS</th>
                                <th class="px-lg py-md font-label-md text-label-md text-secondary text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @forelse($rankings as $item)
                                <tr class="hover:bg-surface-container-low transition-colors group">
                                    <td class="px-lg py-md">
                                        @if($item['rank'] === 1)
                                            <span
                                                class="w-7 h-7 flex items-center justify-center bg-primary text-on-primary rounded-full font-bold text-xs shadow-md">
                                                {{ $item['rank'] }}
                                            </span>
                                        @elseif($item['rank'] <= 3)
                                            <span
                                                class="w-7 h-7 flex items-center justify-center bg-primary-container text-on-primary-container rounded-full font-bold text-xs">
                                                {{ $item['rank'] }}
                                            </span>
                                        @else
                                            <span
                                                class="w-7 h-7 flex items-center justify-center bg-surface-container-highest text-secondary rounded-full font-bold text-xs">
                                                {{ $item['rank'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-lg py-md">
                                        <div class="font-body-md text-body-md font-bold text-on-surface">
                                            {{ $item['supplier']->supplier_name }}
                                        </div>
                                        <div class="text-xs text-secondary">
                                            {{ $item['supplier']->address ?? '-' }}
                                        </div>
                                    </td>
                                    @foreach($criterias as $crit)
                                        @php
                                            $cs = $item['criteria_scores'][$crit->id] ?? null;
                                        @endphp
                                        <td class="px-lg py-md text-center">
                                            @if($cs)
                                                <div class="font-body-md text-body-md text-on-surface">{{ $cs['raw'] }}</div>
                                                <div class="text-[10px] text-secondary">n: {{ $cs['normalized'] }}</div>
                                            @else
                                                <span class="text-secondary">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-lg py-md text-center">
                                        <span class="text-on-surface text-xs">{{ $item['sPlus'] }}</span>
                                    </td>
                                    <td class="px-lg py-md text-center">
                                        <span class="text-on-surface text-xs">{{ $item['sMinus'] }}</span>
                                    </td>
                                    <td class="px-lg py-md text-center">
                                        <span class="text-on-surface text-xs">{{ $item['q'] }}</span>
                                    </td>
                                    <td class="px-lg py-md text-center">
                                        <span class="font-bold {{ $item['rank'] === 1 ? 'text-primary text-lg' : 'text-on-surface' }}">
                                            {{ $item['utility'] }}%
                                        </span>
                                    </td>
                                    <td class="px-lg py-md text-center">
                                        @if($item['utility'] >= 80)
                                            <span
                                                class="px-sm py-[2px] bg-primary-container/10 text-primary-container rounded-full text-[10px] font-black uppercase">
                                                Sangat Baik
                                            </span>
                                        @elseif($item['utility'] >= 60)
                                            <span
                                                class="px-sm py-[2px] bg-primary-container/10 text-primary-container rounded-full text-[10px] font-black uppercase">
                                                Baik
                                            </span>
                                        @elseif($item['utility'] >= 40)
                                            <span
                                                class="px-sm py-[2px] bg-secondary-container/30 text-secondary rounded-full text-[10px] font-black uppercase">
                                                Rata-rata
                                            </span>
                                        @else
                                            <span
                                                class="px-sm py-[2px] bg-error-container/30 text-error rounded-full text-[10px] font-black uppercase">
                                                Kurang
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $criterias->count() + 7 }}" class="px-lg py-xl text-center text-secondary">
                                        @if($search !== '')
                                            Tidak ada supplier yang cocok dengan pencarian "{{ $search }}"
                                        @else
                                            Tidak ada data peringkat.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            {{-- ===== Empty State ===== --}}
            <section class="bg-surface-container-lowest rounded-lg border border-outline-variant p-xl text-center space-y-md">
                <div class="w-20 h-20 bg-surface-container rounded-full flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-4xl text-secondary" data-icon="analytics">analytics</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-on-surface">Belum Ada Data untuk Dihitung</h3>
                    <p class="font-body-md text-body-md text-secondary mt-xs max-w-md mx-auto">
                        Pastikan Anda sudah menambahkan <strong>Supplier</strong>, <strong>Kriteria</strong>, dan
                        mengisi <strong>Nilai Supplier</strong> pada setiap kriteria sebelum melihat hasil perhitungan COPRAS.
                    </p>
                </div>
                <div class="flex justify-center gap-md pt-md">
                    <div class="flex items-center gap-xs px-md py-sm rounded-full border border-outline-variant text-secondary font-label-md">
                        <span class="material-symbols-outlined text-[18px]" data-icon="storefront">storefront</span>
                        Supplier: {{ $supplierCount }}
                    </div>
                    <div class="flex items-center gap-xs px-md py-sm rounded-full border border-outline-variant text-secondary font-label-md">
                        <span class="material-symbols-outlined text-[18px]" data-icon="tune">tune</span>
                        Kriteria: {{ $criterias->count() }}
                    </div>
                </div>
            </section>
        @endif
    </div>

    <!-- Spacer for Bottom Navigation padding -->
    <div class="h-12"></div>
</div>
