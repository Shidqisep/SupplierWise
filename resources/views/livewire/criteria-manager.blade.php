{{-- ===== CRITERIA TAB ===== --}}
<div id="tab-criteria" class="tab-page">
    <link rel="stylesheet" href="{{ asset('css/criteria.css') }}">

    <!-- Top Bar equivalent -->
    <header class="flex justify-between items-center mb-xl">
        <div>
            <h2 class="font-display-lg text-display-lg text-on-surface">Manajemen Kriteria</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Konfigurasi bobot dan parameter evaluasi rantai pasok</p>
        </div>
    </header>

    <!-- Total Weight Tracker (Bento-inspired Summary) -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
        <div class="md:col-span-2 bg-primary text-on-primary p-lg rounded-lg shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <span class="bg-white/20 backdrop-blur-md px-md py-xs rounded-full font-label-md uppercase tracking-wider">Status Validasi</span>
                    <h3 class="font-headline-md text-headline-md mt-sm">Total Bobot Akumulasi</h3>
                </div>
                <div class="flex items-end gap-md">
                    <span id="criteria-total-weight" class="text-7xl font-black leading-none">0%</span>
                    <div class="mb-2 flex items-center gap-sm" id="criteria-validation-status">
                        <span class="material-symbols-outlined text-red-300" data-icon="error">error</span>
                        <span class="font-body-md">Menghitung...</span>
                    </div>
                </div>
            </div>
            <!-- Decorative background elements -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary-container/20 rounded-full blur-3xl"></div>
        </div>
        <div class="bg-white border border-outline-variant p-lg rounded-lg shadow-[0_4px_20px_rgba(15,23,42,0.04)] flex flex-col justify-center">
            <p class="font-label-md text-secondary uppercase tracking-widest mb-xs">Kriteria Aktif</p>
            <h4 class="font-display-lg text-display-lg text-primary"><span id="criteria-active-count">0</span> <span class="text-headline-sm font-normal text-secondary">Parameter</span></h4>
            <button onclick="openModal('modal-criteria')" class="mt-md w-full bg-surface-container-low text-primary py-sm rounded-DEFAULT font-label-md hover:bg-surface-container-high transition-all flex items-center justify-center gap-sm">
                <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span> Tambah Kriteria
            </button>
        </div>
    </section>

    <!-- Criteria List (Asymmetric Layout) -->
    <section class="space-y-md" id="criteria-cards-container">
        <div class="p-xl text-center text-secondary font-body-md">Memuat data...</div>
    </section>
</div>