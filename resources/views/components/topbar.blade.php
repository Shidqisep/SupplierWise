{{-- ============================================================ --}}
{{-- TOP APP BAR --}}
{{-- ============================================================ --}}
<header class="bg-surface/80 backdrop-blur-md shadow-[0_4px_20px_rgba(15,23,42,0.04)] fixed top-0 right-0 left-64 h-16 z-30 flex justify-between items-center px-lg">
    <!-- <div class="flex items-center bg-surface-container-low px-md py-xs rounded-full border border-outline-variant w-96">
        <span class="material-symbols-outlined text-secondary">search</span>
        <input id="search-input" class="bg-transparent border-none focus:ring-0 text-body-md font-body-md w-full" placeholder="Cari supplier, kriteria..." type="text"/>
    </div> -->
    <div class="flex items-center gap-md ml-auto">
        <!-- <button class="p-sm text-secondary hover:bg-surface-container-low transition-colors rounded-full active:scale-95">
            <span class="material-symbols-outlined">history</span>
        </button>
        <button class="p-sm text-secondary hover:bg-surface-container-low transition-colors rounded-full relative active:scale-95">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-error rounded-full"></span>
        </button> -->
        <a href="{{ route('profile') }}" class="flex items-center gap-sm ml-sm hover:bg-surface-container-low p-xs rounded-lg transition-colors">
            <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-primary font-bold text-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="font-label-md text-label-md text-on-surface">{{ auth()->user()->name ?? 'Admin' }}</span>
        </a>
    </div>
</header>