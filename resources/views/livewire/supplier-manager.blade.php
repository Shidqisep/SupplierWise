{{-- ===== SUPPLIERS TAB ===== --}}
    <div id="tab-suppliers" class="tab-page">
        <section class="mb-xl flex justify-between items-end">
            <div>
                <h2 class="font-display-lg text-display-lg text-primary">Suppliers</h2>
                <p class="font-body-lg text-body-lg text-secondary mt-xs">Kelola data semua supplier Anda.</p>
            </div>
            <button onclick="openModal('modal-supplier')"
                class="bg-primary text-on-primary px-lg py-md rounded-DEFAULT flex items-center gap-md font-label-md text-label-md shadow-[0_4px_12px_rgba(0,79,53,0.2)] hover:shadow-[0_6px_16px_rgba(0,79,53,0.3)] transition-all active:scale-95">
                <span class="material-symbols-outlined">add</span>
                TAMBAH SUPPLIER
            </button>
        </section>

        <div class="bg-surface-container-lowest border border-outline-subtle rounded-lg shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">ID</th>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Kategori</th>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Nama Supplier</th>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Kontak</th>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Alamat</th>
                            <th class="text-left px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="supplier-table-body">
                        <tr><td colspan="5" class="px-lg py-xl text-center text-secondary font-body-md">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>