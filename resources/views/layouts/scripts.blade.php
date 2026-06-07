{{-- ============================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================ --}}
<script>
// ─── Config ───────────────────────────────────────────────────────────────────
const API = {
    categories: '{{ url("/api/categories") }}',
    suppliers:  '{{ url("/api/suppliers") }}',
    criteria:   '{{ url("/api/criteria") }}',
    values:     '{{ url("/api/supplier-values") }}',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF };

// ─── State ────────────────────────────────────────────────────────────────────
let categories = [], suppliers = [], criteria = [], values = [];

// ─── Toast ────────────────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    document.getElementById('toast-message').textContent = msg;
    document.getElementById('toast-icon').textContent = type === 'success' ? 'check_circle' : 'error';
    t.classList.toggle('bg-on-surface', type === 'success');
    t.classList.toggle('bg-error', type === 'error');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}

// ─── Modals ───────────────────────────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
// Close on backdrop click
document.querySelectorAll('.modal-backdrop').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});


// ─── API Helpers ──────────────────────────────────────────────────────────────
async function apiFetch(url, options = {}) {
    const res = await fetch(url, { headers, ...options });
    if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || 'Request gagal');
    }
    return res.status === 204 ? null : res.json();
}

// ─── CATEGORIES ──────────────────────────────────────────────────────────────
async function loadCategories() {
    categories = await apiFetch(API.categories);
    populateCategoryDropdown();
}

function populateCategoryDropdown() {
    const sel = document.getElementById('supplier-category');
    if (!sel) return;
    const cur = sel.value;
    sel.innerHTML = '<option value="">-- Pilih Kategori --</option>' + 
        categories.map(c => `<option value="${c.id}" ${c.id == cur ? 'selected' : ''}>${esc(c.category_name)}</option>`).join('');
}

function toggleNewCategoryInput() {
    const el = document.getElementById('new-category-container');
    if (el) el.classList.toggle('hidden');
}

async function saveNewCategory() {
    const input = document.getElementById('new-category-name');
    const val = input.value.trim();
    if (!val) return;
    try {
        const newCat = await apiFetch(API.categories, { method: 'POST', body: JSON.stringify({ category_name: val }) });
        showToast('Kategori baru ditambahkan');
        input.value = '';
        toggleNewCategoryInput();
        await loadCategories();
        document.getElementById('supplier-category').value = newCat.id;
    } catch (e) {
        showToast(e.message, 'error');
    }
}

// ─── SUPPLIERS ───────────────────────────────────────────────────────────────
async function loadSuppliers() {
    suppliers = await apiFetch(API.suppliers);
    renderSupplierTable();
    updateDashCounts();
    populateValueDropdowns();
    computeAndRenderDashboard();
}

function renderSupplierTable() {
    const tbody = document.getElementById('supplier-table-body');
    if (!tbody) return;
    if (!suppliers.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-lg py-xl text-center text-secondary font-body-md">Belum ada data supplier.</td></tr>`;
        return;
    }
    tbody.innerHTML = suppliers.map(s => `
        <tr class="border-b border-outline-subtle hover:bg-surface-container-low transition-colors">
            <td class="px-lg py-md font-body-md text-secondary">${s.id}</td>
            <td class="px-lg py-md">
                ${s.category ? `<span class="px-sm py-1 bg-primary-container/10 text-primary-fixed-dim rounded text-xs font-bold uppercase tracking-wide border border-primary-container/20">${esc(s.category.category_name)}</span>` : '<span class="text-secondary text-xs">—</span>'}
            </td>
            <td class="px-lg py-md font-body-lg text-on-surface font-semibold">
                <a href="/suppliers/${s.id}/values" class="text-primary hover:underline transition-colors">${esc(s.supplier_name)}</a>
            </td>
            <td class="px-lg py-md font-body-md text-secondary">${esc(s.contact ?? '—')}</td>
            <td class="px-lg py-md font-body-md text-secondary">${esc(s.address ?? '—')}</td>
            <td class="px-lg py-md">
                <div class="flex gap-sm">
                    <a href="/suppliers/${s.id}/values"
                        class="p-xs text-tertiary hover:bg-tertiary-fixed rounded transition-colors" title="Kelola Nilai">
                        <span class="material-symbols-outlined text-[18px]">assignment</span>
                    </a>
                    <button onclick="editSupplier(${s.id})"
                        class="p-xs text-primary hover:bg-primary-fixed rounded transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <button onclick="confirmDelete('supplier', ${s.id})"
                        class="p-xs text-error hover:bg-error-container rounded transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </td>
        </tr>`).join('');
}

function openSupplierModal() {
    document.getElementById('supplier-edit-id').value = '';
    document.getElementById('supplier-category').value = '';
    document.getElementById('supplier-name').value = '';
    document.getElementById('supplier-contact').value = '';
    document.getElementById('supplier-address').value = '';
    document.getElementById('modal-supplier-title').textContent = 'Tambah Supplier';
    openModal('modal-supplier');
}

function editSupplier(id) {
    const s = suppliers.find(x => x.id === id);
    if (!s) return;
    document.getElementById('supplier-edit-id').value = s.id;
    document.getElementById('supplier-category').value = s.category_id || '';
    document.getElementById('supplier-name').value = s.supplier_name;
    document.getElementById('supplier-contact').value = s.contact ?? '';
    document.getElementById('supplier-address').value = s.address ?? '';
    document.getElementById('modal-supplier-title').textContent = 'Edit Supplier';
    openModal('modal-supplier');
}

async function saveSupplier() {
    const id = document.getElementById('supplier-edit-id').value;
    const catId = document.getElementById('supplier-category').value;
    const body = {
        category_id: catId ? parseInt(catId) : null,
        supplier_name: document.getElementById('supplier-name').value.trim(),
        contact: document.getElementById('supplier-contact').value.trim() || null,
        address: document.getElementById('supplier-address').value.trim() || null,
    };
    if (!body.supplier_name) { showToast('Nama supplier wajib diisi', 'error'); return; }
    try {
        if (id) {
            await apiFetch(`${API.suppliers}/${id}`, { method: 'PUT', body: JSON.stringify(body) });
            showToast('Supplier berhasil diperbarui');
        } else {
            await apiFetch(API.suppliers, { method: 'POST', body: JSON.stringify(body) });
            showToast('Supplier berhasil ditambahkan');
        }
        closeModal('modal-supplier');
        await loadSuppliers();
    } catch (e) { showToast(e.message, 'error'); }
}

// ─── CRITERIA ────────────────────────────────────────────────────────────────
async function loadCriteria() {
    criteria = await apiFetch(API.criteria);
    renderCriteriaTable();
    updateDashCounts();
    populateValueDropdowns();
    computeAndRenderDashboard();
}

function renderCriteriaTable() {
    const container = document.getElementById('criteria-cards-container');
    
    if (!container) return;
    
    if (!criteria.length) {
        container.innerHTML = `<div class="p-xl text-center text-secondary font-body-md">Belum ada data kriteria.</div>`;
        updateCriteriaStats();
        return;
    }
    
    const totalWeight = criteria.reduce((sum, c) => sum + parseFloat(c.weight), 0);

    container.innerHTML = criteria.map(c => {
        const weightVal = parseFloat(c.weight);
        const weightPct = (weightVal * 100).toFixed(0);
        
        // Calculate max allowed for this specific slider
        const othersWeight = totalWeight - weightVal;
        let maxAllowedDec = 1.0 - othersWeight;
        if (maxAllowedDec < 0) maxAllowedDec = 0;
        if (maxAllowedDec > 1) maxAllowedDec = 1;
        const maxAllowedPct = Math.floor(maxAllowedDec * 100);

        return `
        <div class="glass-card p-xl rounded-lg flex flex-col md:flex-row gap-xl hover:shadow-md transition-all group ${c.type === 'benefit' ? 'border-l-4 border-primary' : 'border-l-4 border-error'}">
            <div class="flex-shrink-0 w-16 h-16 ${c.type === 'benefit' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container'} rounded-lg flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">rule</span>
            </div>
            <div class="flex-grow space-y-md">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="font-headline-sm text-headline-sm">${esc(c.criteria_name)}</h5>
                        <p class="font-body-md text-secondary">ID: ${c.id}</p>
                    </div>
                    <div class="flex items-center gap-sm">
                        <span class="px-md py-xs ${c.type === 'benefit' ? 'bg-primary-container text-on-primary-container' : 'bg-error-container text-on-error-container'} rounded-full font-label-md uppercase">${c.type === 'benefit' ? 'BENEFIT' : 'COST'}</span>
                        <button onclick="editCriteria(${c.id})" class="p-2 text-secondary hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <button onclick="confirmDelete('criteria', ${c.id})" class="p-2 text-secondary hover:text-error transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-sm">
                    <div class="flex justify-between font-label-md text-secondary">
                        <span>Bobot Kepentingan</span>
                        <span class="font-bold text-primary" id="slider-label-${c.id}">${weightPct}%</span>
                    </div>
                    <input oninput="document.getElementById('slider-label-${c.id}').textContent = this.value + '%'" onchange="quickUpdateWeight(${c.id}, this.value)" class="w-full h-2 bg-surface-container rounded-full appearance-none cursor-pointer slider-thumb" max="${maxAllowedPct}" min="0" type="range" value="${weightPct}"/>
                </div>
            </div>
        </div>`;
    }).join('');

    updateCriteriaStats();
}

async function quickUpdateWeight(id, val) {
    const c = criteria.find(x => x.id === id);
    if (!c) return;

    // Convert percentage (e.g., 40) back to decimal (0.4)
    const newWeight = parseFloat(val) / 100;

    const body = {
        criteria_name: c.criteria_name,
        type: c.type,
        weight: newWeight
    };

    try {
        await apiFetch(`${API.criteria}/${id}`, { method: 'PUT', body: JSON.stringify(body) });
        showToast('Bobot kriteria diperbarui');
        await loadCriteria();
    } catch (e) {
        showToast('Gagal mengupdate bobot: ' + e.message, 'error');
        renderCriteriaTable(); // Revert the slider UI
    }
}

function updateCriteriaStats() {
    const activeCountEl = document.getElementById('criteria-active-count');
    const totalWeightEl = document.getElementById('criteria-total-weight');
    const validationStatusEl = document.getElementById('criteria-validation-status');

    if (!activeCountEl || !totalWeightEl || !validationStatusEl) return;

    activeCountEl.textContent = criteria.length;
    
    let totalWeight = criteria.reduce((sum, c) => sum + parseFloat(c.weight), 0);
    // Typically weights sum to 1.0. Let's check against 1.0 or 100 depending on how they store it.
    let percentage = (totalWeight * 100).toFixed(0);
    if (totalWeight > 1.5) percentage = totalWeight.toFixed(0); // If they store 100 directly

    totalWeightEl.textContent = percentage + '%';
    
    // Validate if total weight is approximately 1.0 or 100
    if (Math.abs(totalWeight - 1.0) < 0.01 || Math.abs(totalWeight - 100) < 0.01) {
        validationStatusEl.innerHTML = `
            <span class="material-symbols-outlined text-primary" data-icon="check_circle">check_circle</span>
            <span class="font-body-md text-primary font-semibold">Sesuai standar AHP</span>
        `;
    } else {
        validationStatusEl.innerHTML = `
            <span class="material-symbols-outlined text-error" data-icon="error">error</span>
            <span class="font-body-md text-error font-semibold">Bobot belum 100%</span>
        `;
    }
}

function editCriteria(id) {
    const c = criteria.find(x => x.id === id);
    if (!c) return;
    document.getElementById('criteria-edit-id').value = c.id;
    document.getElementById('criteria-name').value = c.criteria_name;
    document.getElementById('criteria-type').value = c.type;
    document.getElementById('criteria-weight').value = c.weight;
    document.getElementById('modal-criteria-title').textContent = 'Edit Kriteria';
    openModal('modal-criteria');
}

async function saveCriteria() {
    const id = document.getElementById('criteria-edit-id').value;
    const body = {
        criteria_name: document.getElementById('criteria-name').value.trim(),
        type: document.getElementById('criteria-type').value,
        weight: parseFloat(document.getElementById('criteria-weight').value),
    };
    if (!body.criteria_name) { showToast('Nama kriteria wajib diisi', 'error'); return; }
    if (isNaN(body.weight) || body.weight < 0) { showToast('Bobot harus berupa angka positif', 'error'); return; }

    // Validate total weight limit
    let othersWeight = 0;
    if (id) {
        othersWeight = criteria.reduce((sum, c) => sum + (c.id == id ? 0 : parseFloat(c.weight)), 0);
    } else {
        othersWeight = criteria.reduce((sum, c) => sum + parseFloat(c.weight), 0);
    }
    
    let maxAllowedWeight = 1.0 - othersWeight;
    if (maxAllowedWeight < 0) maxAllowedWeight = 0;
    
    // Allow small floating point tolerance
    if (body.weight > maxAllowedWeight + 0.001) {
        showToast(`Bobot melebihi batas. Maksimal tersisa: ${maxAllowedWeight.toFixed(2)} (${Math.floor(maxAllowedWeight*100)}%)`, 'error');
        return;
    }

    try {
        if (id) {
            await apiFetch(`${API.criteria}/${id}`, { method: 'PUT', body: JSON.stringify(body) });
            showToast('Kriteria berhasil diperbarui');
        } else {
            await apiFetch(API.criteria, { method: 'POST', body: JSON.stringify(body) });
            showToast('Kriteria berhasil ditambahkan');
        }
        closeModal('modal-criteria');
        await loadCriteria();
    } catch (e) { showToast(e.message, 'error'); }
}

// ─── VALUES ───────────────────────────────────────────────────────────────────
async function loadValues() {
    values = await apiFetch(API.values);
    renderValuesTable();
    renderRanking();
    updateDashStats();
    computeAndRenderDashboard();
}

function populateValueDropdowns() {
    const supSel = document.getElementById('value-supplier');
    if (!supSel) return;
    const criSel = document.getElementById('value-criteria');
    if (!criSel) return;
    const curSupId = supSel.value;
    const curCriId = criSel.value;
    supSel.innerHTML = '<option value="">Pilih Supplier...</option>' +
        suppliers.map(s => `<option value="${s.id}" ${s.id == curSupId ? 'selected' : ''}>${esc(s.supplier_name)}</option>`).join('');
    criSel.innerHTML = '<option value="">Pilih Kriteria...</option>' +
        criteria.map(c => `<option value="${c.id}" ${c.id == curCriId ? 'selected' : ''}>${esc(c.criteria_name)}</option>`).join('');
}

function renderValuesTable() {
    const tbody = document.getElementById('values-table-body');
    if (!tbody) return;
    if (!values.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-lg py-xl text-center text-secondary font-body-md">Belum ada data nilai.</td></tr>`;
        return;
    }
    tbody.innerHTML = values.map(v => `
        <tr class="border-b border-outline-subtle hover:bg-surface-container-low transition-colors">
            <td class="px-lg py-md font-body-md text-secondary">${v.id}</td>
            <td class="px-lg py-md font-body-lg text-on-surface">${esc(v.supplier?.supplier_name ?? '—')}</td>
            <td class="px-lg py-md font-body-md text-secondary">${esc(v.criteria?.criteria_name ?? '—')}</td>
            <td class="px-lg py-md font-body-md text-on-surface font-semibold">${v.score}</td>
            <td class="px-lg py-md">
                <div class="flex gap-sm">
                    <button onclick="editValue(${v.id})"
                        class="p-xs text-primary hover:bg-primary-fixed rounded transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <button onclick="confirmDelete('value', ${v.id})"
                        class="p-xs text-error hover:bg-error-container rounded transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </td>
        </tr>`).join('');
}

function editValue(id) {
    const v = values.find(x => x.id === id);
    if (!v) return;
    document.getElementById('value-edit-id').value = v.id;
    document.getElementById('value-supplier').value = v.id_supplier;
    document.getElementById('value-criteria').value = v.id_criteria;
    document.getElementById('value-score').value = v.score;
    document.getElementById('modal-value-title').textContent = 'Edit Nilai';
    openModal('modal-value');
}

async function saveValue() {
    const id = document.getElementById('value-edit-id').value;
    const body = {
        id_supplier: parseInt(document.getElementById('value-supplier').value),
        id_criteria: parseInt(document.getElementById('value-criteria').value),
        score: parseFloat(document.getElementById('value-score').value),
    };
    if (!body.id_supplier) { showToast('Pilih supplier terlebih dahulu', 'error'); return; }
    if (!body.id_criteria) { showToast('Pilih kriteria terlebih dahulu', 'error'); return; }
    if (isNaN(body.score)) { showToast('Skor harus berupa angka', 'error'); return; }
    try {
        if (id) {
            await apiFetch(`${API.values}/${id}`, { method: 'PUT', body: JSON.stringify(body) });
            showToast('Nilai berhasil diperbarui');
        } else {
            await apiFetch(API.values, { method: 'POST', body: JSON.stringify(body) });
            showToast('Nilai berhasil ditambahkan');
        }
        closeModal('modal-value');
        // Reset score input
        document.getElementById('value-edit-id').value = '';
        document.getElementById('value-score').value = '';
        await loadValues();
    } catch (e) { showToast(e.message, 'error'); }
}

// ─── DELETE ───────────────────────────────────────────────────────────────────
function confirmDelete(type, id) {
    const btn = document.getElementById('confirm-delete-btn');
    btn.onclick = () => doDelete(type, id);
    openModal('modal-delete');
}

async function doDelete(type, id) {
    const urlMap = { supplier: API.suppliers, criteria: API.criteria, value: API.values };
    try {
        await apiFetch(`${urlMap[type]}/${id}`, { method: 'DELETE' });
        showToast('Data berhasil dihapus');
        closeModal('modal-delete');
        if (type === 'supplier') await loadSuppliers();
        if (type === 'criteria') await loadCriteria();
        if (type === 'value') await loadValues();
    } catch (e) { showToast(e.message, 'error'); }
}

// ─── Dashboard computations ───────────────────────────────────────────────────
function updateDashCounts() {
    const elSup = document.getElementById('dash-total-suppliers');
    const elCri = document.getElementById('dash-total-criteria');
    if (elSup) elSup.textContent = suppliers.length;
    if (elCri) elCri.textContent = criteria.length;
}

function updateDashStats() {

    const totalEl = document.getElementById('dash-total-values');
    const avgEl   = document.getElementById('dash-avg-score');
    const maxEl   = document.getElementById('dash-max-score');

    if (!totalEl || !avgEl || !maxEl) return;

    const scores = values.map(v => parseFloat(v.score));

    totalEl.textContent = values.length;

    avgEl.textContent = scores.length
        ? (scores.reduce((a,b)=>a+b,0)/scores.length).toFixed(2)
        : '—';

    maxEl.textContent = scores.length
        ? Math.max(...scores).toFixed(2)
        : '—';
}

/**
 * Compute simple weighted sum ranking (SAW-like).
 * For "benefit" criteria: higher score = better.
 * For "cost" criteria: lower score = better (inverted).
 * 
 * ⚠️ NOTE FOR DEVELOPER:
 * This is a client-side approximation using raw scores.
 * For production, implement a proper SAW/TOPSIS endpoint
 * in a new ResultController with normalized matrix calculation.
 */
function computeRanking() {
    if (!suppliers.length || !criteria.length || !values.length) return [];

    // Build score map: { supplierId: { criteriaId: score } }
    const scoreMap = {};
    values.forEach(v => {
        if (!scoreMap[v.id_supplier]) scoreMap[v.id_supplier] = {};
        scoreMap[v.id_supplier][v.id_criteria] = parseFloat(v.score);
    });

    // Find min/max per criteria for normalization
    const minMax = {};
    criteria.forEach(c => {
        const vals = values.filter(v => v.id_criteria == c.id).map(v => parseFloat(v.score));
        minMax[c.id] = { min: Math.min(...vals), max: Math.max(...vals) };
    });

    // Compute weighted score per supplier
    const ranking = suppliers.map(s => {
        let total = 0;
        criteria.forEach(c => {
            const raw = scoreMap[s.id]?.[c.id];
            if (raw === undefined) return;
            const { min, max } = minMax[c.id];
            let norm = (max === min) ? 1 : (c.type === 'benefit')
                ? (raw - min) / (max - min)
                : (max - raw) / (max - min);
            total += norm * parseFloat(c.weight);
        });
        return { supplier: s, total: total };
    });

    return ranking.sort((a, b) => b.total - a.total);
}

function renderRanking() {
    const ranking = computeRanking();
    const tbody = document.getElementById('ranking-table-body');
    if (!tbody) return;
    if (!ranking.length) {
        tbody.innerHTML = `<tr><td colspan="4" class="px-lg py-xl text-center text-secondary font-body-md">Belum cukup data untuk dihitung.</td></tr>`;
        return;
    }
    tbody.innerHTML = ranking.map((r, i) => {
        const pct = Math.min(100, (r.total * 100)).toFixed(1);
        const rankIcons = ['🥇', '🥈', '🥉'];
        return `
        <tr class="border-b border-outline-subtle hover:bg-surface-container-low transition-colors">
            <td class="px-lg py-md font-body-lg font-bold text-on-surface">
                ${rankIcons[i] ?? `#${i + 1}`}
            </td>
            <td class="px-lg py-md font-body-lg text-on-surface">${esc(r.supplier.supplier_name)}</td>
            <td class="px-lg py-md font-body-md text-on-surface font-semibold">${r.total.toFixed(4)}</td>
            <td class="px-lg py-md w-48">
                <div class="flex items-center gap-sm">
                    <div class="flex-1 bg-surface-container rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full transition-all duration-700" style="width: ${pct}%"></div>
                    </div>
                    <span class="font-label-md text-label-md text-secondary w-10 text-right">${pct}%</span>
                </div>
            </td>
        </tr>`;
    }).join('');
}

function computeAndRenderDashboard() {
    const bestName  = document.getElementById('best-supplier-name');
    const bestDesc  = document.getElementById('best-supplier-desc');
    const bestScore = document.getElementById('best-supplier-score');
    const bestTotal = document.getElementById('best-supplier-total');
    const chart     = document.getElementById('dash-chart');

    // Dashboard tidak ada di halaman ini
    if (!bestName || !bestDesc || !bestScore || !bestTotal || !chart) {
        return;
    }

    if (!suppliers.length || !criteria.length || !values.length) {
        bestName.textContent = "Belum Ada Data";
        bestDesc.textContent = "Tambahkan data supplier, kriteria, dan nilai untuk melihat hasil ranking terbaik di sini.";
        bestScore.textContent = "0";
        bestTotal.innerHTML = `<span class="material-symbols-outlined">trending_up</span>0`;
        const placeholder = document.getElementById('dash-chart-placeholder');
        if (placeholder) {
            placeholder.textContent = "Belum cukup data untuk menampilkan chart.";
        }
        return;
    }

    const ranking = computeRanking();

    if (!ranking.length) {
        bestName.textContent = "Belum Ada Data";
        return;
    }

    const best = ranking[0];

    bestName.textContent = best.supplier.supplier_name;

    bestDesc.textContent =
        `Performa tertinggi berdasarkan ${criteria.length} kriteria yang aktif. Total weighted score terbaik dari ${suppliers.length} supplier yang ada.`;

    const score = (best.total * 100).toFixed(1);

    bestScore.textContent = score;

    bestTotal.innerHTML =
        `<span class="material-symbols-outlined">trending_up</span>${best.total.toFixed(4)}`;

    const placeholder = document.getElementById('dash-chart-placeholder');

    if (placeholder) {
        placeholder.remove();
    }

    const top = ranking.slice(0, 7);
    const maxVal = top[0]?.total || 1;

    chart.querySelectorAll('.chart-bar-col').forEach(el => el.remove());

    top.forEach(r => {

        const heightPct = Math.max(
            5,
            (r.total / maxVal) * 90
        ).toFixed(1);

        const col = document.createElement('div');

        col.className =
            'chart-bar-col flex-1 flex flex-col justify-end group';

        col.innerHTML = `
            <div
                class="bg-primary-fixed w-full rounded-t-sm group-hover:bg-primary transition-colors relative"
                style="height:${heightPct}%">

                <div
                    class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-surface px-sm py-xs rounded text-[10px] opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    ${(r.total * 100).toFixed(1)}%
                </div>

            </div>

            <p
                class="text-center font-label-md text-label-md text-secondary mt-sm truncate max-w-full px-xs"
                style="font-size:10px">
                ${esc(r.supplier.supplier_name.split(' ')[0])}
            </p>
        `;

        chart.appendChild(col);
    });
}

// ─── Utils ────────────────────────────────────────────────────────────────────
function esc(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Search ───────────────────────────────────────────────────────────────────


// ─── Featured card hover ─────────────────────────────────────────────────────
const featuredCard = document.getElementById('featured-card');
if (featuredCard) {
    featuredCard.addEventListener('mousemove', e => {
        const r = featuredCard.getBoundingClientRect();
        featuredCard.style.background = `radial-gradient(circle at ${e.clientX - r.left}px ${e.clientY - r.top}px, #005f40 0%, #004f35 70%)`;
    });
    featuredCard.addEventListener('mouseleave', () => {
        featuredCard.style.background = '#004f35';
    });
}

// ─── Init ─────────────────────────────────────────────────────────────────────
(async function init() {
    try {
        await loadCategories();
        await Promise.all([loadSuppliers(), loadCriteria()]);
        await loadValues();
    } catch (e) {
        showToast('Gagal memuat data: ' + e.message, 'error');
    }
})();
</script>
