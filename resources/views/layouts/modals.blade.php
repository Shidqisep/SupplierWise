    {{-- MODAL GLOBAL --}}
    {{-- SUPPLIER MODAL --}}
<div id="modal-supplier" class="modal-backdrop">
    <div class="bg-surface-container-lowest rounded-lg p-xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-lg">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-supplier-title">Tambah Supplier</h3>
            <button onclick="closeModal('modal-supplier')" class="text-secondary hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <input type="hidden" id="supplier-edit-id"/>
        <div class="flex flex-col gap-md">
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Kategori Supplier</label>
                <div class="flex gap-sm items-center">
                    <select id="supplier-category" class="flex-grow border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface">
                        <option value="">-- Pilih Kategori --</option>
                    </select>
                    <button onclick="toggleNewCategoryInput()" class="p-xs text-primary hover:bg-surface-container-low rounded transition-colors" title="Tambah Kategori Baru">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    </button>
                </div>
                <div id="new-category-container" class="hidden mt-sm flex gap-sm items-center">
                    <input id="new-category-name" type="text" placeholder="Nama Kategori Baru" class="flex-grow border border-outline-variant rounded-DEFAULT px-sm py-[4px] font-body-md focus:ring-2 focus:ring-primary outline-none bg-surface"/>
                    <button onclick="saveNewCategory()" class="bg-primary text-on-primary px-sm py-[4px] rounded text-xs hover:opacity-90 transition-opacity">Simpan</button>
                </div>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Nama Supplier *</label>
                <input id="supplier-name" type="text" placeholder="Contoh: Global Logistics Co."
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface"/>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Kontak</label>
                <input id="supplier-contact" type="text" placeholder="No. HP / Email"
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface"/>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Alamat</label>
                <textarea id="supplier-address" rows="2" placeholder="Alamat lengkap..."
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface resize-none"></textarea>
            </div>
        </div>
        <div class="flex gap-md mt-xl">
            <button onclick="closeModal('modal-supplier')"
                class="flex-1 border border-outline-variant text-secondary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                BATAL
            </button>
            <button onclick="saveSupplier()"
                class="flex-1 bg-primary text-on-primary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:opacity-90 transition-opacity active:scale-95">
                SIMPAN
            </button>
        </div>
    </div>
</div>

{{-- CRITERIA MODAL --}}
<div id="modal-criteria" class="modal-backdrop">
    <div class="bg-surface-container-lowest rounded-lg p-xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-lg">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-criteria-title">Tambah Kriteria</h3>
            <button onclick="closeModal('modal-criteria')" class="text-secondary hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <input type="hidden" id="criteria-edit-id"/>
        <div class="flex flex-col gap-md">
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Nama Kriteria *</label>
                <input id="criteria-name" type="text" placeholder="Contoh: Harga, Kualitas..."
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface"/>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Tipe *</label>
                <select id="criteria-type"
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface">
                    <option value="benefit">Benefit (Semakin tinggi semakin baik)</option>
                    <option value="cost">Cost (Semakin rendah semakin baik)</option>
                </select>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Bobot *</label>
                <input id="criteria-weight" type="number" step="0.01" min="0" placeholder="Contoh: 0.25"
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface"/>
            </div>
        </div>
        <div class="flex gap-md mt-xl">
            <button onclick="closeModal('modal-criteria')"
                class="flex-1 border border-outline-variant text-secondary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                BATAL
            </button>
            <button onclick="saveCriteria()"
                class="flex-1 bg-primary text-on-primary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:opacity-90 transition-opacity active:scale-95">
                SIMPAN
            </button>
        </div>
    </div>
</div>

{{-- SUPPLIER VALUE MODAL --}}
<div id="modal-value" class="modal-backdrop">
    <div class="bg-surface-container-lowest rounded-lg p-xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-lg">
            <h3 class="font-headline-sm text-headline-sm text-on-surface" id="modal-value-title">Input Nilai Supplier</h3>
            <button onclick="closeModal('modal-value')" class="text-secondary hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <input type="hidden" id="value-edit-id"/>
        <div class="flex flex-col gap-md">
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Supplier *</label>
                <select id="value-supplier"
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface">
                    <option value="">Pilih Supplier...</option>
                </select>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Kriteria *</label>
                <select id="value-criteria"
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface">
                    <option value="">Pilih Kriteria...</option>
                </select>
            </div>
            <div>
                <label class="font-label-md text-label-md text-secondary block mb-xs">Skor *</label>
                <input id="value-score" type="number" step="0.01" placeholder="Masukkan nilai..."
                    class="w-full border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none bg-surface"/>
            </div>
        </div>
        <div class="flex gap-md mt-xl">
            <button onclick="closeModal('modal-value')"
                class="flex-1 border border-outline-variant text-secondary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                BATAL
            </button>
            <button onclick="saveValue()"
                class="flex-1 bg-primary text-on-primary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:opacity-90 transition-opacity active:scale-95">
                SIMPAN
            </button>
        </div>
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
<div id="modal-delete" class="modal-backdrop">
    <div class="bg-surface-container-lowest rounded-lg p-xl w-full max-w-sm mx-4 shadow-2xl">
        <div class="flex flex-col items-center text-center gap-md">
            <div class="w-16 h-16 rounded-full bg-error-container flex items-center justify-center">
                <span class="material-symbols-outlined text-error text-[32px]">delete_forever</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-on-surface">Hapus Data?</h3>
            <p class="font-body-lg text-body-lg text-secondary">Tindakan ini tidak bisa dibatalkan. Data akan dihapus secara permanen.</p>
        </div>
        <div class="flex gap-md mt-xl">
            <button onclick="closeModal('modal-delete')"
                class="flex-1 border border-outline-variant text-secondary px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:bg-surface-container-low transition-colors">
                BATAL
            </button>
            <button id="confirm-delete-btn"
                class="flex-1 bg-error text-on-error px-lg py-md rounded-DEFAULT font-label-md text-label-md hover:opacity-90 transition-opacity active:scale-95">
                YA, HAPUS
            </button>
        </div>
    </div>
</div>
