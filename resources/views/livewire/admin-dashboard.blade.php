<main class="pb-xl">

    {{-- ===== DASHBOARD TAB ===== --}}
    <div id="tab-dashboard" class="tab-page active">
        {{-- Welcome Header --}}
        <section class="flex justify-between items-center">
            <div>
                <h2 class="font-display-lg text-display-lg text-primary">Halo, {{ auth()->user()->name ?? 'Admin' }}</h2>
                <p class="font-body-lg text-body-lg text-secondary mt-xs">Berikut adalah ringkasan performa rantai pasok Anda.</p>
            </div>
            <a href="{{ route('suppliers', ['open' => 'create']) }}"
   class="bg-primary text-on-primary px-lg py-md rounded-DEFAULT flex items-center gap-md">
    <span class="material-symbols-outlined">add</span>
    TAMBAH SUPPLIER
</a>
        </section>

        <div class="grid grid-cols-12 gap-6">
            {{-- Stat Cards --}}
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-gutter">
                <div class="stat-card bg-surface-container-lowest border border-outline-subtle p-lg rounded-DEFAULT shadow-[0_4px_20px_rgba(15,23,42,0.04)] flex items-center gap-lg">
                    <div class="w-14 h-14 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[28px]">groups</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Suppliers</p>
                        <h3 class="font-display-lg text-display-lg text-on-surface" id="dash-total-suppliers">—</h3>
                    </div>
                </div>
                <div class="stat-card bg-surface-container-lowest border border-outline-subtle p-lg rounded-DEFAULT shadow-[0_4px_20px_rgba(15,23,42,0.04)] flex items-center gap-lg">
                    <div class="w-14 h-14 rounded-full bg-surface-container flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[28px]">verified</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-secondary uppercase tracking-wider">Active Criteria</p>
                        <h3 class="font-display-lg text-display-lg text-on-surface" id="dash-total-criteria">—</h3>
                    </div>
                </div>
            </div>

            {{-- Featured Card: Best Supplier --}}
            <div class="col-span-12 lg:col-span-8">
                <div id="featured-card" class="relative h-full min-h-[300px] rounded-lg overflow-hidden bg-primary p-xl flex flex-col justify-between text-on-primary cursor-default">
                    <div class="absolute -top-12 -right-12 w-64 h-64 bg-primary-container/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute top-1/2 -left-12 w-48 h-48 bg-on-primary-container/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-sm mb-md">
                            <span class="bg-white/20 backdrop-blur-md px-md py-xs rounded-full font-label-md text-label-md flex items-center gap-xs">
                                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1;">stars</span>
                                SUPPLIER TERBAIK SAAT INI
                            </span>
                        </div>
                        <h3 class="font-display-lg text-display-lg mb-sm" id="best-supplier-name">Memuat data...</h3>
                        <p class="font-body-lg text-body-lg text-on-primary/80 max-w-md" id="best-supplier-desc">
                            Kalkulasi sedang dalam proses. Pastikan data supplier dan kriteria sudah lengkap.
                        </p>
                    </div>
                    <div class="relative z-10 flex items-end justify-between">
                        <div class="flex gap-xl">
                            <div>
                                <p class="font-label-md text-label-md text-on-primary/60 mb-xs">Decision Score</p>
                                <div class="flex items-baseline gap-xs">
                                    <span class="text-[48px] font-extrabold leading-none" id="best-supplier-score">—</span>
                                    <span class="font-label-md text-label-md text-on-primary-container">/ 100</span>
                                </div>
                            </div>
                            <div class="w-px h-12 bg-white/10 self-center"></div>
                            <div>
                                <p class="font-label-md text-label-md text-on-primary/60 mb-xs">Total Nilai</p>
                                <div class="flex items-center gap-xs text-on-primary-container font-bold text-headline-sm" id="best-supplier-total">
                                    <span class="material-symbols-outlined">trending_up</span>—
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('results') }}"
   class="bg-white text-primary px-lg py-md rounded-DEFAULT">
    LIHAT DETAIL
</a>
                    </div>
                </div>
            </div>

            {{-- Weekly Insights Section --}}
            {{--
                ⚠️ CATATAN: Bagian "Insight Mingguan" ini memerlukan endpoint baru di backend.
                Kamu perlu membuat:
                1. Sebuah route & controller method, contoh: GET /api/analytics/weekly
                   yang mengembalikan data performa supplier per hari/minggu dari tabel supplier_values.
                Sementara ini ditampilkan data ringkasan statis dari data yang sudah ada.
            --}}
            <div class="col-span-12">
                <div class="bg-surface-container-lowest border border-outline-subtle rounded-lg shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
                    <div class="px-lg py-md border-b border-outline-subtle flex justify-between items-center bg-surface-bright">
                        <div class="flex items-center gap-md">
                            <span class="material-symbols-outlined text-primary">insights</span>
                            <h4 class="font-headline-sm text-headline-sm text-on-surface">Ringkasan Nilai Supplier</h4>
                        </div>
                        <span class="px-md py-xs rounded-full bg-surface-container text-secondary font-label-md text-label-md">DARI DATABASE</span>
                    </div>
                    <div class="p-lg">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-xl mb-lg" id="dash-summary-stats">
                            <div class="p-md rounded-DEFAULT bg-surface-container-low border border-outline-subtle">
                                <p class="font-label-md text-label-md text-secondary">Total Nilai Input</p>
                                <p class="font-headline-md text-headline-md text-on-surface" id="dash-total-values">—</p>
                            </div>
                            <div class="p-md rounded-DEFAULT bg-surface-container-low border border-outline-subtle">
                                <p class="font-label-md text-label-md text-secondary">Rata-rata Skor</p>
                                <p class="font-headline-md text-headline-md text-on-surface" id="dash-avg-score">—</p>
                            </div>
                            <div class="p-md rounded-DEFAULT bg-surface-container-low border border-outline-subtle">
                                <p class="font-label-md text-label-md text-secondary">Skor Tertinggi</p>
                                <p class="font-headline-md text-headline-md text-on-surface" id="dash-max-score">—</p>
                            </div>
                        </div>

                        {{-- Bar chart: top suppliers by total weighted score --}}
                        <div class="h-64 w-full relative flex items-end gap-gutter mt-xl px-md" id="dash-chart">
                            <div class="absolute inset-0 flex flex-col justify-between opacity-10 pointer-events-none">
                                <div class="border-t border-on-surface w-full"></div>
                                <div class="border-t border-on-surface w-full"></div>
                                <div class="border-t border-on-surface w-full"></div>
                                <div class="border-t border-on-surface w-full"></div>
                            </div>
                            <p class="text-secondary font-label-md text-label-md self-center w-full text-center" id="dash-chart-placeholder">Memuat chart...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>