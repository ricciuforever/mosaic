<?php require resource_path('views/layouts/header.blade.php'); ?>

<style>
    /* Multi-Leg & Multi-Category Notch Bar */
    .composer-notch {
        position: sticky;
        top: 74px;
        z-index: 80;
        background: rgba(11, 15, 23, 0.96);
        backdrop-filter: blur(18px);
        border-bottom: 1px solid var(--gold-border);
        padding: 14px 0;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.55);
    }
    .legs-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 1px solid var(--border-subtle);
        flex-wrap: wrap;
    }
    .leg-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .leg-tab {
        padding: 7px 15px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border-subtle);
        background: rgba(255, 255, 255, 0.04);
        color: var(--text-sand);
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .leg-tab.active {
        border-color: var(--gold);
        background: var(--gold-muted);
        color: var(--gold-bright);
    }
    .cat-stepper {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 10px;
        align-items: center;
    }
    .cat-step-slot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 14px;
        border: 1px solid var(--border-subtle);
        background: rgba(255, 255, 255, 0.03);
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
    }
    .cat-step-slot:hover {
        border-color: rgba(200, 155, 82, 0.45);
    }
    .cat-step-slot.active {
        border-color: var(--gold);
        background: rgba(200, 155, 82, 0.14);
        box-shadow: 0 0 0 1px var(--gold);
    }
    .cat-step-slot.filled {
        border-color: rgba(52, 211, 153, 0.45);
    }

    /* Filter Toolbar */
    .compose-filters {
        background: var(--bg-deep);
        border-bottom: 1px solid var(--border-subtle);
        padding: 12px 0;
    }
    .filters-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .filter-control {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
        padding: 8px 12px;
        color: var(--text-ivory);
        font-size: 12.5px;
    }

    /* Split Screen Layout (Cards Left + Interactive Map Right) */
    .compose-split {
        display: grid;
        grid-template-columns: 56% 44%;
        min-height: calc(100vh - 230px);
    }
    .compose-catalog-pane {
        padding: 24px 28px 60px;
    }
    .compose-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    .compose-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.22s ease;
    }
    .compose-card:hover, .compose-card.hovered {
        border-color: var(--gold);
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(0, 0, 0, 0.65);
    }
    .compose-card.selected-card {
        border: 2px solid var(--gold-bright);
        box-shadow: 0 0 28px rgba(200, 155, 82, 0.28);
    }

    /* Map Pane & Custom Price Pill Markers */
    .compose-map-pane {
        position: sticky;
        top: 205px;
        height: calc(100vh - 205px);
        border-left: 1px solid var(--border-subtle);
        background: #080c14;
    }
    #compose-leaflet-map {
        width: 100%;
        height: 100%;
        background: #080c14;
    }
    .map-overlay-top {
        position: absolute;
        top: 16px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 500;
        background: rgba(11, 15, 23, 0.92);
        backdrop-filter: blur(12px);
        border: 1px solid var(--gold-border);
        border-radius: var(--radius-pill);
        padding: 8px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-ivory);
        box-shadow: 0 10px 30px rgba(0,0,0,0.65);
        white-space: nowrap;
    }
    .mosaic-price-pin {
        background: #111723;
        color: #f5f1e8;
        border: 1.5px solid #c89b52;
        border-radius: 999px;
        padding: 4px 10px;
        font-family: var(--font-sans);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 6px 16px rgba(0,0,0,0.75);
        transition: transform 0.18s ease, background 0.18s ease, color 0.18s ease;
        display: inline-block;
    }
    .mosaic-price-pin:hover,
    .mosaic-price-pin.pin-active {
        background: #e4be7a;
        color: #080b11;
        border-color: #ffffff;
        transform: scale(1.16);
        z-index: 999 !important;
    }
    .mosaic-price-pin.pin-selected {
        background: #34d399;
        color: #06130e;
        border-color: #ffffff;
    }
    .leaflet-popup-content-wrapper {
        background: #111723 !important;
        color: #f5f1e8 !important;
        border: 1px solid rgba(200, 155, 82, 0.5);
        border-radius: 16px !important;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 20px 45px rgba(0,0,0,0.85) !important;
    }
    .leaflet-popup-content {
        margin: 0 !important;
        width: 260px !important;
    }
    .leaflet-popup-tip {
        background: #111723 !important;
    }

    @media (max-width: 1080px) {
        .cat-stepper { grid-template-columns: 1fr 1fr; }
        .compose-split { grid-template-columns: 1fr; }
        .compose-map-pane { position: relative; top: 0; height: 440px; order: -1; }
    }
</style>

<!-- TOP COMPOSER NOTCH (MULTI-LEG & 4 CATEGORIES) -->
<section class="composer-notch" aria-label="Stato Composizione Soggiorno">
    <div class="container">
        <div class="legs-bar">
            <div class="leg-tabs" id="composer-leg-tabs">
                <button type="button" class="leg-tab active">
                    <span>Tappa 1 · <?= e($initialState['dest'] ?? 'costa-smeralda') ?></span>
                    <span style="opacity:0.75;font-size:11px;">(<?= (int)($initialState['days'] ?? 4) ?> gg · <?= e($initialState['start'] ?? '15 Lug 2026') ?> – <?= e($initialState['end'] ?? '19 Lug 2026') ?>)</span>
                </button>
            </div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <select id="leg-dest-select" class="filter-control" aria-label="Destinazione Tappa">
                    <?php foreach ($destinations as $d): ?>
                        <option value="<?= e($d['slug']) ?>"><?= e($d['name']) ?> (<?= e($d['region']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <select id="leg-days-select" class="filter-control" aria-label="Giorni Tappa">
                    <option value="2">2 notti</option>
                    <option value="3">3 notti</option>
                    <option value="4">4 notti</option>
                    <option value="5">5 notti</option>
                    <option value="7">7 notti</option>
                    <option value="10">10 notti</option>
                </select>
                <select id="leg-adults-select" class="filter-control" aria-label="Ospiti Tappa">
                    <option value="2">2 Ospiti</option>
                    <option value="4">4 Ospiti</option>
                    <option value="6">6 Ospiti</option>
                    <option value="8">8 Ospiti</option>
                    <option value="10">10 Ospiti</option>
                </select>
                <button type="button" class="btn btn-ghost btn-sm" id="btn-add-leg">
                    + Aggiungi Tappa
                </button>
            </div>
        </div>

        <div class="cat-stepper">
            <button type="button" class="cat-step-slot active" data-slot-cat="villa" onclick="setActiveCategory('villa')">
                <div>
                    <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-bright);">1 · Dimora</div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--text-ivory);" id="slot-label-villa">Scegli Villa</div>
                </div>
                <span style="font-size:18px;">🏛️</span>
            </button>

            <button type="button" class="cat-step-slot" data-slot-cat="auto" onclick="setActiveCategory('auto')">
                <div>
                    <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-bright);">2 · Supercar / SUV</div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--text-ivory);" id="slot-label-auto">Scegli Auto</div>
                </div>
                <span style="font-size:18px;">🏎️</span>
            </button>

            <button type="button" class="cat-step-slot" data-slot-cat="yacht" onclick="setActiveCategory('yacht')">
                <div>
                    <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-bright);">3 · Mare & Charter</div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--text-ivory);" id="slot-label-yacht">Scegli Yacht</div>
                </div>
                <span style="font-size:18px;">🛥️</span>
            </button>

            <button type="button" class="cat-step-slot" data-slot-cat="jet" onclick="setActiveCategory('jet')">
                <div>
                    <div style="font-size:10.5px;text-transform:uppercase;letter-spacing:0.08em;color:var(--gold-bright);">4 · Volo Privato</div>
                    <div style="font-size:13.5px;font-weight:600;color:var(--text-ivory);" id="slot-label-jet">Scegli Jet / Heli</div>
                </div>
                <span style="font-size:18px;">✈️</span>
            </button>

            <div style="display:flex;align-items:center;gap:14px;padding-left:8px;">
                <div style="text-align:right;">
                    <div style="font-size:10.5px;text-transform:uppercase;color:var(--text-muted);">Totale Stimato</div>
                    <div style="font-size:20px;font-weight:700;color:var(--gold-bright);" id="composer-total-price">€0</div>
                </div>
                <a href="/richiesta" class="btn btn-gold" id="btn-proceed-request">
                    Invia richiesta →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- DYNAMIC CATEGORY FILTER BAR -->
<section class="compose-filters" aria-label="Filtri Catalogo">
    <div class="container filters-row">
        <select id="filter-sort" class="filter-control" aria-label="Ordina per">
            <option value="featured">Ordina: Consigliati dal Concierge</option>
            <option value="price_asc">Prezzo: crescente</option>
            <option value="price_desc">Prezzo: decrescente</option>
            <option value="capacity_desc">Capienza: dalla più ampia</option>
        </select>

        <select id="filter-subtype" class="filter-control" aria-label="Tipologia">
            <option value="all">Tutte le tipologie</option>
        </select>

        <select id="filter-max-price" class="filter-control" aria-label="Budget massimo">
            <option value="999999">Qualsiasi fascia di prezzo</option>
            <option value="2500">Fino a €2.500 / unità</option>
            <option value="5000">Fino a €5.000 / unità</option>
            <option value="8500">Fino a €8.500 / unità</option>
            <option value="15000">Fino a €15.000 / unità</option>
        </select>

        <select id="filter-min-cap" class="filter-control" aria-label="Capienza minima">
            <option value="1">Qualsiasi capienza</option>
            <option value="2">Almeno 2 ospiti/posti</option>
            <option value="4">Almeno 4 ospiti/posti</option>
            <option value="8">Almeno 8 ospiti</option>
            <option value="10">Almeno 10 ospiti</option>
        </select>

        <input type="search" id="filter-search" class="filter-control" placeholder="Cerca nome, modello o località..." style="min-width:210px;">

        <button type="button" class="btn btn-ghost btn-sm" id="btn-reset-filters">
            Reset filtri
        </button>

        <span style="margin-left:auto;font-size:12.5px;color:var(--text-sand);" id="compose-results-counter">
            Caricamento offerte...
        </span>
    </div>
</section>

<!-- SPLIT SCREEN: LEFT CARDS + RIGHT INTERACTIVE LEAFLET MAP -->
<div class="compose-split">
    <div class="compose-catalog-pane">
        <div style="margin-bottom:18px;display:flex;justify-content:space-between;align-items:baseline;flex-wrap:wrap;gap:10px;">
            <div>
                <h1 class="serif" style="font-size:32px;font-weight:500;" id="compose-heading-title">Seleziona la tua Dimora</h1>
                <p style="font-size:13px;color:var(--text-sand);" id="compose-heading-sub">
                    Passa il cursore sulle schede per localizzarle sulla mappa interattiva o clicca un segnaposto per l'anteprima rapida.
                </p>
            </div>
        </div>

        <div class="compose-cards-grid" id="compose-cards-container">
            <!-- Rendered dynamically from catalog JSON for instant map/filter sync -->
        </div>
    </div>

    <aside class="compose-map-pane" aria-label="Mappa Geografica Interattiva Esperienze">
        <div class="map-overlay-top">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;">
                <input type="checkbox" id="map-bbox-toggle" style="accent-color:var(--gold);width:15px;height:15px;">
                <span>Cerca mentre muovi la mappa</span>
            </label>
        </div>
        <div id="compose-leaflet-map"></div>
    </aside>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    const MOSAIC_CATALOG = <?= json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const MOSAIC_DESTINATIONS = <?= json_encode($destinations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const INITIAL_QUERY = <?= json_encode($initialState, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    const CAT_META = {
        villa: { title: 'Seleziona la tua Villa o Dimora Storica', icon: '🏛️', emptyLabel: 'Scegli Villa' },
        auto:  { title: 'Seleziona Supercar o SUV di Rappresentanza', icon: '🏎️', emptyLabel: 'Scegli Auto' },
        yacht: { title: 'Seleziona Yacht, Day Cruiser o Motoscafo d\'Epoca', icon: '🛥️', emptyLabel: 'Scegli Yacht' },
        jet:   { title: 'Seleziona Jet Privato o Elicottero VIP', icon: '✈️', emptyLabel: 'Scegli Jet / Heli' }
    };

    // Load or initialize multi-leg Dossier state
    let dossierState = {
        activeLegIndex: 0,
        activeCategory: INITIAL_QUERY.cats[0] || 'villa',
        legs: [
            {
                destination: INITIAL_QUERY.dest || 'costa-smeralda',
                days: INITIAL_QUERY.days || 4,
                adults: INITIAL_QUERY.adults || 2,
                children: INITIAL_QUERY.children || 0,
                startDate: INITIAL_QUERY.start || '15 Lug 2026',
                endDate: INITIAL_QUERY.end || '19 Lug 2026',
                selected: { villa: null, auto: null, yacht: null, jet: null }
            }
        ]
    };

    // Restore from localStorage and sanitize against active catalog
    try {
        const savedRaw = localStorage.getItem('mosaic_dossier_v2');
        if (savedRaw) {
            const parsed = JSON.parse(savedRaw);
            if (parsed && Array.isArray(parsed.legs) && parsed.legs.length > 0) {
                parsed.legs.forEach((leg) => {
                    if (leg && leg.selected && typeof leg.selected === 'object') {
                        ['villa', 'auto', 'yacht', 'jet'].forEach((c) => {
                            const code = leg.selected[c];
                            if (code && !MOSAIC_CATALOG.some(p => p.code === code)) {
                                leg.selected[c] = null;
                            }
                        });
                    }
                });
                dossierState = parsed;
            }
        }
    } catch (e) {}

    // Apply explicit URL query parameters (e.g. /compose?dest=costa-smeralda&from=2026-11-08&to=2026-11-15&adults=2&children=0&cats=auto)
    if (INITIAL_QUERY.hasExplicitQuery) {
        const activeIdx = dossierState.activeLegIndex || 0;
        const activeLeg = dossierState.legs[activeIdx] || dossierState.legs[0];
        if (activeLeg) {
            if (INITIAL_QUERY.dest) activeLeg.destination = INITIAL_QUERY.dest;
            if (INITIAL_QUERY.days) activeLeg.days = INITIAL_QUERY.days;
            if (INITIAL_QUERY.adults) activeLeg.adults = INITIAL_QUERY.adults;
            if (typeof INITIAL_QUERY.children === 'number') activeLeg.children = INITIAL_QUERY.children;
            if (INITIAL_QUERY.start) activeLeg.startDate = INITIAL_QUERY.start;
            if (INITIAL_QUERY.end) activeLeg.endDate = INITIAL_QUERY.end;
        }
        if (INITIAL_QUERY.cats && INITIAL_QUERY.cats[0]) {
            dossierState.activeCategory = INITIAL_QUERY.cats[0];
        }
    }

    // Apply URL preselect codes (e.g. from AI Concierge or Home card click)
    if (Array.isArray(INITIAL_QUERY.preselect) && INITIAL_QUERY.preselect.length > 0) {
        INITIAL_QUERY.preselect.forEach((code) => {
            const prod = MOSAIC_CATALOG.find(p => p.code === code || p.slug === code);
            if (prod) {
                dossierState.legs[0].destination = prod.destination_slug;
                dossierState.legs[0].selected[prod.category] = prod.code;
                dossierState.activeCategory = prod.category;
            }
        });
        if (INITIAL_QUERY.cats && INITIAL_QUERY.cats[0]) {
            dossierState.activeCategory = INITIAL_QUERY.cats[0];
        }
    }

    function saveDossierState() {
        try {
            localStorage.setItem('mosaic_dossier_v2', JSON.stringify(dossierState));
            if (typeof updateDossierCounter === 'function') updateDossierCounter();
        } catch (e) {}
    }
    saveDossierState();

    // Initialize Leaflet Map with CartoDB Dark Matter tiles
    const map = L.map('compose-leaflet-map', {
        center: [41.115, 9.535],
        zoom: 12,
        zoomControl: true
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO · MOSAIC Luxury Map',
        subdomains: 'abcd',
        maxZoom: 18
    }).addTo(map);

    let markersByCode = {};
    let suppressNextMoveFilter = false;

    function flyToDestination(destSlug) {
        const d = MOSAIC_DESTINATIONS.find(x => x.slug === destSlug);
        if (!d) return;
        suppressNextMoveFilter = true;
        map.flyTo([parseFloat(d.lat), parseFloat(d.lng)], parseInt(d.zoom_level || 12, 10), {
            duration: 0.9
        });
        setTimeout(() => { suppressNextMoveFilter = false; }, 1100);
    }

    function currentLeg() {
        return dossierState.legs[dossierState.activeLegIndex] || dossierState.legs[0];
    }

    function populateSubtypeOptions() {
        const cat = dossierState.activeCategory;
        const select = document.getElementById('filter-subtype');
        const currentVal = select.value;
        const subtypes = Array.from(new Set(
            MOSAIC_CATALOG.filter(p => p.category === cat && p.subtype).map(p => p.subtype)
        ));
        select.innerHTML = '<option value="all">Tutte le tipologie</option>';
        subtypes.forEach((st) => {
            const opt = document.createElement('option');
            opt.value = st;
            opt.textContent = st;
            if (st === currentVal) opt.selected = true;
            select.appendChild(opt);
        });
    }

    function renderNotchBar() {
        const leg = currentLeg();
        const tabsContainer = document.getElementById('composer-leg-tabs');
        tabsContainer.innerHTML = '';

        dossierState.legs.forEach((l, idx) => {
            const destObj = MOSAIC_DESTINATIONS.find(d => d.slug === l.destination);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'leg-tab ' + (idx === dossierState.activeLegIndex ? 'active' : '');
            const dateBadge = (l.startDate && l.endDate) ? ` · ${l.startDate} – ${l.endDate}` : '';
            btn.innerHTML = `<span>Tappa ${idx + 1}: ${destObj ? destObj.name : l.destination}${dateBadge} (${l.days}g)</span>`;
            btn.addEventListener('click', () => {
                dossierState.activeLegIndex = idx;
                syncControlsFromLeg();
                flyToDestination(currentLeg().destination);
                renderAll();
            });

            if (dossierState.legs.length > 1) {
                const delSpan = document.createElement('span');
                delSpan.textContent = '✕';
                delSpan.style.marginLeft = '6px';
                delSpan.style.opacity = '0.7';
                delSpan.title = 'Rimuovi tappa';
                delSpan.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    dossierState.legs.splice(idx, 1);
                    if (dossierState.activeLegIndex >= dossierState.legs.length) {
                        dossierState.activeLegIndex = dossierState.legs.length - 1;
                    }
                    saveDossierState();
                    syncControlsFromLeg();
                    flyToDestination(currentLeg().destination);
                    renderAll();
                });
                btn.appendChild(delSpan);
            }
            tabsContainer.appendChild(btn);
        });

        // Update 4 category slots
        ['villa', 'auto', 'yacht', 'jet'].forEach((cat) => {
            const slotBtn = document.querySelector(`.cat-step-slot[data-slot-cat="${cat}"]`);
            const labelEl = document.getElementById(`slot-label-${cat}`);
            const selectedCode = leg.selected[cat];
            const prod = selectedCode ? MOSAIC_CATALOG.find(p => p.code === selectedCode) : null;

            slotBtn.classList.toggle('active', dossierState.activeCategory === cat);
            slotBtn.classList.toggle('filled', Boolean(prod));
            labelEl.textContent = prod ? `${prod.title} (€${prod.price.toLocaleString('it-IT')})` : CAT_META[cat].emptyLabel;
        });

        // Calculate total across all legs
        let grandTotal = 0;
        dossierState.legs.forEach((l) => {
            Object.entries(l.selected).forEach(([cat, code]) => {
                if (!code) return;
                const p = MOSAIC_CATALOG.find(x => x.code === code);
                if (p) {
                    const mult = (cat === 'jet') ? 1 : l.days;
                    grandTotal += p.price * mult;
                }
            });
        });
        document.getElementById('composer-total-price').textContent = '€' + grandTotal.toLocaleString('it-IT');
    }

    function getFilteredProducts() {
        const leg = currentLeg();
        const cat = dossierState.activeCategory;
        const sort = document.getElementById('filter-sort').value;
        const subtype = document.getElementById('filter-subtype').value;
        const maxPrice = parseInt(document.getElementById('filter-max-price').value, 10) || 999999;
        const minCap = parseInt(document.getElementById('filter-min-cap').value, 10) || 1;
        const q = document.getElementById('filter-search').value.trim().toLowerCase();
        const useBbox = document.getElementById('map-bbox-toggle').checked;
        const bounds = useBbox ? map.getBounds() : null;

        let list = MOSAIC_CATALOG.filter((p) => {
            if (p.category !== cat) return false;
            if (!useBbox && p.destination_slug !== leg.destination) return false;
            if (subtype !== 'all' && (p.subtype || '').toLowerCase() !== subtype.toLowerCase()) return false;
            if (p.price > maxPrice) return false;
            if (p.capacity < minCap) return false;
            if (q !== '') {
                const hay = `${p.title} ${p.subtitle} ${p.location} ${p.subtype || ''}`.toLowerCase();
                if (!hay.includes(q)) return false;
            }
            if (bounds) {
                const lat = parseFloat(p.lat);
                const lng = parseFloat(p.lng);
                if (!bounds.contains([lat, lng])) return false;
            }
            return true;
        });

        if (sort === 'price_asc') {
            list.sort((a, b) => a.price - b.price);
        } else if (sort === 'price_desc') {
            list.sort((a, b) => b.price - a.price);
        } else if (sort === 'capacity_desc') {
            list.sort((a, b) => b.capacity - a.capacity);
        } else {
            list.sort((a, b) => (Number(b.featured) - Number(a.featured)) || (b.price - a.price));
        }

        return list;
    }

    function toggleSelectProduct(code) {
        const prod = MOSAIC_CATALOG.find(p => p.code === code);
        if (!prod) return;
        const leg = currentLeg();
        if (leg.selected[prod.category] === code) {
            leg.selected[prod.category] = null;
        } else {
            leg.selected[prod.category] = code;
        }
        saveDossierState();
        renderAll();
    }
    window.toggleSelectProduct = toggleSelectProduct;

    function highlightPin(code, active) {
        const marker = markersByCode[code];
        if (!marker || !marker._icon) return;
        const pill = marker._icon.querySelector('.mosaic-price-pin');
        if (pill) {
            pill.classList.toggle('pin-active', active);
        }
    }

    function renderCardsAndMarkers() {
        const leg = currentLeg();
        const cat = dossierState.activeCategory;
        const filtered = getFilteredProducts();

        document.getElementById('compose-heading-title').textContent = CAT_META[cat].title;
        document.getElementById('compose-results-counter').textContent = `${filtered.length} offerte disponibili`;

        const container = document.getElementById('compose-cards-container');
        container.innerHTML = '';

        // Clear existing map markers
        Object.values(markersByCode).forEach(m => map.removeLayer(m));
        markersByCode = {};

        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="grid-column:1/-1;padding:42px;text-align:center;background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:16px;">
                    <div class="serif" style="font-size:26px;color:var(--gold-bright);margin-bottom:8px;">Nessuna offerta nei limiti attuali</div>
                    <p style="font-size:13.5px;color:var(--text-sand);margin-bottom:18px;">Prova ad ampliare i filtri o disattivare il filtro "Cerca mentre muovi la mappa".</p>
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('btn-reset-filters').click()">Ripristina tutti i filtri</button>
                </div>
            `;
            return;
        }

        const selectedCode = leg.selected[cat];
        const mult = (cat === 'jet') ? 1 : leg.days;

        filtered.forEach((p) => {
            const isSelected = (selectedCode === p.code);
            const totalForStay = p.price * mult;
            const img = (p.images && p.images[0]) ? p.images[0] : '/images/hero.jpg';

            // 1. Create Card
            const card = document.createElement('article');
            card.className = 'compose-card ' + (isSelected ? 'selected-card' : '');
            card.dataset.code = p.code;
            card.innerHTML = `
                <a href="/prodotto/${p.slug}" style="position:relative;aspect-ratio:16/10;overflow:hidden;background:#0d131d;display:block;">
                    <img src="${img}" alt="${p.title}" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                    <div style="position:absolute;top:10px;left:10px;right:10px;display:flex;justify-content:space-between;gap:6px;">
                        <span class="badge badge-gold">${p.subtype || p.category}</span>
                        ${p.badge ? `<span class="badge badge-green">${p.badge}</span>` : ''}
                    </div>
                </a>
                <div style="padding:18px;display:flex;flex-direction:column;flex:1;">
                    <div style="display:flex;justify-content:space-between;font-size:11.5px;color:var(--gold-bright);margin-bottom:4px;">
                        <span>📍 ${p.location}</span>
                        <span>${p.capacity_label}</span>
                    </div>
                    <a href="/prodotto/${p.slug}" class="serif" style="font-size:22px;font-weight:600;color:var(--text-ivory);margin-bottom:4px;">${p.title}</a>
                    <div style="font-size:12.5px;color:var(--text-sand);margin-bottom:14px;">${p.subtitle}</div>
                    <div style="margin-top:auto;padding-top:12px;border-top:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;gap:10px;">
                        <div>
                            <div style="font-size:17px;font-weight:700;color:var(--text-ivory);">€${Number(p.price).toLocaleString('it-IT')} <span style="font-size:11.5px;font-weight:400;color:var(--text-muted);">/ ${p.unit}</span></div>
                            <div style="font-size:11px;color:var(--gold-bright);">Totale ${mult} ${p.unit}: €${Number(totalForStay).toLocaleString('it-IT')}</div>
                        </div>
                        <div style="display:flex;gap:6px;">
                            <a href="/prodotto/${p.slug}" class="btn btn-ghost btn-sm">Scheda</a>
                            <button type="button" class="btn ${isSelected ? 'btn-outline' : 'btn-gold'} btn-sm" onclick="toggleSelectProduct('${p.code}')">
                                ${isSelected ? 'Selezionato ✓' : 'Scegli +'}
                            </button>
                        </div>
                    </div>
                </div>
            `;

            card.addEventListener('mouseenter', () => highlightPin(p.code, true));
            card.addEventListener('mouseleave', () => highlightPin(p.code, false));
            container.appendChild(card);

            // 2. Create Map Price Pill Marker
            const divIcon = L.divIcon({
                className: 'mosaic-pin-wrapper',
                html: `<div class="mosaic-price-pin ${isSelected ? 'pin-selected' : ''}">€${Number(p.price).toLocaleString('it-IT')}</div>`,
                iconSize: [76, 30],
                iconAnchor: [38, 15]
            });

            const marker = L.marker([parseFloat(p.lat), parseFloat(p.lng)], { icon: divIcon }).addTo(map);
            const popupHtml = `
                <div>
                    <img src="${img}" alt="${p.title}" style="width:100%;height:135px;object-fit:cover;">
                    <div style="padding:12px 14px;">
                        <div style="font-size:10.5px;color:#e4be7a;text-transform:uppercase;">${p.location}</div>
                        <div style="font-family:var(--font-serif);font-size:19px;font-weight:600;margin:2px 0 4px;">${p.title}</div>
                        <div style="font-size:12px;color:#c5beb0;margin-bottom:10px;">€${Number(p.price).toLocaleString('it-IT')} / ${p.unit}</div>
                        <div style="display:flex;gap:6px;">
                            <a href="/prodotto/${p.slug}" class="btn btn-ghost btn-sm" style="flex:1;">Dettagli</a>
                            <button type="button" class="btn btn-gold btn-sm" style="flex:1;" onclick="toggleSelectProduct('${p.code}')">
                                ${isSelected ? 'Rimuovi' : 'Seleziona'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            marker.bindPopup(popupHtml);
            marker.on('mouseover', () => card.classList.add('hovered'));
            marker.on('mouseout', () => card.classList.remove('hovered'));
            markersByCode[p.code] = marker;
        });
    }

    function setActiveCategory(cat) {
        dossierState.activeCategory = cat;
        saveDossierState();
        populateSubtypeOptions();
        renderAll();
    }
    window.setActiveCategory = setActiveCategory;

    function syncControlsFromLeg() {
        const leg = currentLeg();
        document.getElementById('leg-dest-select').value = leg.destination;

        const daysSelect = document.getElementById('leg-days-select');
        const daysStr = String(leg.days);
        if (!Array.from(daysSelect.options).some(o => o.value === daysStr)) {
            const opt = document.createElement('option');
            opt.value = daysStr;
            opt.textContent = `${daysStr} ${leg.days === 1 ? 'notte' : 'notti'}`;
            daysSelect.appendChild(opt);
        }
        daysSelect.value = daysStr;

        const adultsSelect = document.getElementById('leg-adults-select');
        const adultsStr = String(leg.adults);
        if (!Array.from(adultsSelect.options).some(o => o.value === adultsStr)) {
            const opt = document.createElement('option');
            opt.value = adultsStr;
            opt.textContent = `${adultsStr} ${leg.adults === 1 ? 'Ospite' : 'Ospiti'}`;
            adultsSelect.appendChild(opt);
        }
        adultsSelect.value = adultsStr;
    }

    function renderAll() {
        renderNotchBar();
        renderCardsAndMarkers();
    }

    // Event Listeners
    document.getElementById('leg-dest-select').addEventListener('change', (e) => {
        currentLeg().destination = e.target.value;
        document.getElementById('map-bbox-toggle').checked = false;
        saveDossierState();
        flyToDestination(e.target.value);
        renderAll();
    });

    document.getElementById('leg-days-select').addEventListener('change', (e) => {
        currentLeg().days = parseInt(e.target.value, 10) || 4;
        saveDossierState();
        renderAll();
    });

    document.getElementById('leg-adults-select').addEventListener('change', (e) => {
        currentLeg().adults = parseInt(e.target.value, 10) || 2;
        saveDossierState();
        renderAll();
    });

    document.getElementById('btn-add-leg').addEventListener('click', () => {
        const nextDests = ['costa-smeralda', 'costiera-amalfitana', 'lago-di-como'];
        const used = dossierState.legs.map(l => l.destination);
        const pick = nextDests.find(d => !used.includes(d)) || 'costiera-amalfitana';
        dossierState.legs.push({
            destination: pick,
            days: 4,
            adults: currentLeg().adults,
            children: 0,
            startDate: '20 Lug 2026',
            endDate: '24 Lug 2026',
            selected: { villa: null, auto: null, yacht: null, jet: null }
        });
        dossierState.activeLegIndex = dossierState.legs.length - 1;
        saveDossierState();
        syncControlsFromLeg();
        flyToDestination(pick);
        renderAll();
    });

    ['filter-sort', 'filter-subtype', 'filter-max-price', 'filter-min-cap', 'map-bbox-toggle'].forEach((id) => {
        document.getElementById(id).addEventListener('change', () => renderCardsAndMarkers());
    });
    document.getElementById('filter-search').addEventListener('input', () => renderCardsAndMarkers());

    document.getElementById('btn-reset-filters').addEventListener('click', () => {
        document.getElementById('filter-sort').value = 'featured';
        document.getElementById('filter-subtype').value = 'all';
        document.getElementById('filter-max-price').value = '999999';
        document.getElementById('filter-min-cap').value = '1';
        document.getElementById('filter-search').value = '';
        document.getElementById('map-bbox-toggle').checked = false;
        renderCardsAndMarkers();
    });

    map.on('moveend', () => {
        if (!suppressNextMoveFilter && document.getElementById('map-bbox-toggle').checked) {
            renderCardsAndMarkers();
        }
    });

    // Initial boot
    syncControlsFromLeg();
    populateSubtypeOptions();
    flyToDestination(currentLeg().destination);
    renderAll();
</script>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
