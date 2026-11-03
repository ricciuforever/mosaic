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

    /* Map Pane & Custom Floating Controls (MapLibre GL + OpenFreeMap Dark Luxury) */
    .compose-map-pane {
        position: sticky;
        top: 205px;
        height: calc(100vh - 205px);
        border-left: 1px solid var(--border-subtle);
        background: radial-gradient(120% 90% at 78% 12%, #101a20 0%, #0c141b 42%, #0a1016 100%);
        overflow: hidden;
    }
    #compose-map-container {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
    .map-overlay-top {
        position: absolute;
        top: 18px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 4;
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
        <div id="compose-map-mode-wrapper" data-map-mode="real" style="position:absolute;inset:0;background:radial-gradient(120% 90% at 78% 12%, #101a20 0%, #0c141b 42%, #0a1016 100%);">
            <div id="compose-map-container" class="maplibregl-map" style="position:absolute;inset:0;"></div>
            <div id="compose-faux-map" style="display:none;position:absolute;inset:0;overflow:hidden;background:radial-gradient(120% 90% at 78% 12%, #101a20 0%, #0c141b 42%, #0a1016 100%);">
                <div style="position:absolute;inset:0;opacity:0.05;background:repeating-linear-gradient(56deg,rgba(200,220,210,0.7) 0 1px,transparent 1px 26px),repeating-linear-gradient(-38deg,rgba(200,220,210,0.5) 0 1px,transparent 1px 34px);"></div>
                <div style="position:absolute;left:54%;top:44%;width:230px;height:230px;transform:translate(-50%,-50%);border-radius:50%;background:radial-gradient(circle,rgba(198,154,78,0.22),rgba(198,154,78,0.06) 46%,transparent 70%);"></div>
                <div style="position:absolute;left:54%;top:44%;width:150px;height:150px;transform:translate(-50%,-50%);border-radius:50%;border:1px dashed rgba(198,168,104,0.4);"></div>
                <div id="compose-faux-pins" style="position:absolute;inset:0;"></div>
            </div>

            <div class="map-overlay-top">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;user-select:none;">
                    <input type="checkbox" id="map-bbox-toggle" style="accent-color:var(--gold);width:15px;height:15px;">
                    <span>Cerca mentre muovi la mappa</span>
                </label>
            </div>

            <div style="position:absolute;right:18px;top:18px;display:flex;flex-direction:column;align-items:center;gap:12px;z-index:3;">
                <button type="button" id="btn-map-fit-bounds" title="Inquadra i risultati" style="width:44px;height:44px;border-radius:50%;border:1px solid rgba(255,255,255,0.14);background:rgba(12,16,23,0.85);backdrop-filter:blur(8px);color:#d8d3c9;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 6px 16px rgba(0,0,0,0.4);">
                    <svg viewBox="0 0 24 24" style="width:19px;height:19px;fill:none;stroke:currentColor;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
                        <circle cx="12" cy="12" r="4"></circle>
                        <line x1="12" y1="2" x2="12" y2="5"></line>
                        <line x1="12" y1="19" x2="12" y2="22"></line>
                        <line x1="2" y1="12" x2="5" y2="12"></line>
                        <line x1="19" y1="12" x2="22" y2="12"></line>
                    </svg>
                </button>
                <div style="display:flex;flex-direction:column;border-radius:14px;border:1px solid rgba(255,255,255,0.14);background:rgba(12,16,23,0.85);backdrop-filter:blur(8px);overflow:hidden;box-shadow:0 6px 16px rgba(0,0,0,0.4);">
                    <button type="button" id="btn-map-zoom-in" aria-label="Zoom avanti" style="width:44px;height:44px;border:none;background:transparent;color:#d8d3c9;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                        <svg viewBox="0 0 24 24" style="width:19px;height:19px;fill:none;stroke:currentColor;stroke-width:1.7;stroke-linecap:round;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <div style="height:1px;background:rgba(255,255,255,0.12);"></div>
                    <button type="button" id="btn-map-zoom-out" aria-label="Zoom indietro" style="width:44px;height:44px;border:none;background:transparent;color:#d8d3c9;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                        <svg viewBox="0 0 24 24" style="width:19px;height:19px;fill:none;stroke:currentColor;stroke-width:1.7;stroke-linecap:round;">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </aside>
</div>

<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js" crossorigin=""></script>
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

    // OpenFreeMap Dark Luxury Style Recoloring (Exact Prototype Function)
    function recolorOpenFreeMapDarkLuxury(styleJson) {
        const s = typeof structuredClone === 'function' ? structuredClone(styleJson) : JSON.parse(JSON.stringify(styleJson));
        for (const layer of (s.layers ?? [])) {
            const id = layer.id ?? '';
            const paint = (layer.paint = layer.paint ?? {});
            const layout = (layer.layout = layer.layout ?? {});
            switch (layer.type) {
                case 'background':
                    paint['background-color'] = '#131b17';
                    break;
                case 'fill':
                    if (/water|ocean|river|lake/i.test(id)) {
                        paint['fill-color'] = '#0a121d';
                    } else if (/park|wood|grass|forest|landcover|vegetation|cemetery|golf|garden/i.test(id)) {
                        paint['fill-color'] = '#182619';
                    } else if (/building/i.test(id)) {
                        paint['fill-color'] = '#151d26';
                    } else if (/sand|beach|aeroway|rock|glacier/i.test(id)) {
                        paint['fill-color'] = '#1a2220';
                    } else {
                        paint['fill-color'] = '#121a17';
                    }
                    if ('fill-outline-color' in paint) {
                        paint['fill-outline-color'] = 'rgba(200,220,210,0.05)';
                    }
                    break;
                case 'line':
                    if (/boundary|admin/i.test(id)) {
                        paint['line-color'] = 'rgba(198,168,104,0.30)';
                    } else if (/water|river|stream|canal/i.test(id)) {
                        paint['line-color'] = '#12202e';
                    } else if (/rail|transit|ferry/i.test(id)) {
                        paint['line-color'] = 'rgba(200,214,208,0.06)';
                    } else if (/motorway|trunk|primary|major/i.test(id)) {
                        paint['line-color'] = 'rgba(202,214,208,0.17)';
                    } else {
                        paint['line-color'] = 'rgba(202,214,208,0.09)';
                    }
                    break;
                case 'symbol':
                    if (/poi|housenumber|house_num|airport|transit|ferry/i.test(id)) {
                        layout.visibility = 'none';
                        break;
                    }
                    paint['text-color'] = /place|city|town|village|country|state|capital/i.test(id)
                        ? 'rgba(216,211,201,0.8)'
                        : 'rgba(216,211,201,0.5)';
                    paint['text-halo-color'] = 'rgba(10,13,20,0.92)';
                    paint['text-halo-width'] = 1.1;
                    if (layout['icon-image']) {
                        paint['icon-opacity'] = 0.3;
                    }
                    break;
                case 'fill-extrusion':
                    paint['fill-extrusion-color'] = '#121924';
                    break;
            }
        }
        return s;
    }

    let map = null;
    let mapLoaded = false;
    let mapFauxMode = false;
    const markersByCode = new Map();
    let hoveredCode = null;
    let suppressNextMoveFilter = false;

    function currentLeg() {
        return dossierState.legs[dossierState.activeLegIndex] || dossierState.legs[0];
    }

    function activateFauxMapFallback() {
        mapFauxMode = true;
        const wrapper = document.getElementById('compose-map-mode-wrapper');
        const realContainer = document.getElementById('compose-map-container');
        const fauxContainer = document.getElementById('compose-faux-map');
        if (wrapper) wrapper.setAttribute('data-map-mode', 'faux');
        if (realContainer) realContainer.style.display = 'none';
        if (fauxContainer) fauxContainer.style.display = 'block';
        renderFauxPins(getFilteredProducts());
    }

    function renderFauxPins(productsList) {
        const fauxPins = document.getElementById('compose-faux-pins');
        if (!fauxPins) return;
        fauxPins.innerHTML = '';
        const leg = currentLeg();
        const selectedCodes = Object.values(leg.selected).filter(Boolean);
        productsList.forEach((p, idx) => {
            const isSelected = selectedCodes.includes(p.code);
            const isHovered = hoveredCode === p.code && !isSelected;
            const pin = document.createElement('div');
            pin.className = 'msc-pin' + (isSelected ? ' msc-pin--active' : (isHovered ? ' msc-pin--hover' : ''));
            pin.style.position = 'absolute';
            pin.style.left = `${p.map_x || (28 + (idx * 13) % 52)}%`;
            pin.style.top = `${p.map_y || (26 + (idx * 17) % 54)}%`;
            pin.style.transform = 'translate(-50%, -100%)';
            pin.textContent = '€' + Number(p.price).toLocaleString('it-IT');
            pin.addEventListener('click', (ev) => {
                ev.stopPropagation();
                window.location.href = `/prodotto/${p.slug}`;
            });
            fauxPins.appendChild(pin);
        });
    }

    function fitMapToProducts(productsList) {
        if (!map || !mapLoaded) return;
        const d = MOSAIC_DESTINATIONS.find(x => x.slug === currentLeg().destination) || MOSAIC_DESTINATIONS[0];
        suppressNextMoveFilter = true;
        if (!productsList || productsList.length === 0) {
            if (d) {
                map.easeTo({
                    center: [parseFloat(d.lng), parseFloat(d.lat)],
                    zoom: parseFloat(d.zoom_level || 11.4),
                    duration: 900
                });
            }
        } else if (productsList.length === 1) {
            map.easeTo({
                center: [parseFloat(productsList[0].lng), parseFloat(productsList[0].lat)],
                zoom: 11.8,
                duration: 900
            });
        } else {
            const bounds = new maplibregl.LngLatBounds();
            productsList.forEach(p => bounds.extend([parseFloat(p.lng), parseFloat(p.lat)]));
            map.fitBounds(bounds, {
                padding: { top: 80, bottom: 170, left: 80, right: 80 },
                maxZoom: 12.4,
                duration: 900
            });
        }
        setTimeout(() => { suppressNextMoveFilter = false; }, 1000);
    }

    function flyToDestination(destSlug) {
        const d = MOSAIC_DESTINATIONS.find(x => x.slug === destSlug);
        if (!d || !map || !mapLoaded) return;
        suppressNextMoveFilter = true;
        map.easeTo({
            center: [parseFloat(d.lng), parseFloat(d.lat)],
            zoom: parseFloat(d.zoom_level || 11.4),
            duration: 900
        });
        setTimeout(() => { suppressNextMoveFilter = false; }, 1000);
    }

    async function initMapLibre() {
        const initDest = MOSAIC_DESTINATIONS.find(x => x.slug === currentLeg().destination) || MOSAIC_DESTINATIONS[0];
        const initCenter = initDest ? [parseFloat(initDest.lng), parseFloat(initDest.lat)] : [9.535, 41.105];
        const initZoom = initDest ? parseFloat(initDest.zoom_level || 11.4) : 11.4;

        try {
            if (typeof maplibregl === 'undefined') {
                throw new Error('MapLibre GL non disponibile');
            }
            const res = await fetch('https://tiles.openfreemap.org/styles/dark');
            if (!res.ok) {
                throw new Error(`style HTTP ${res.status}`);
            }
            const rawStyle = await res.json();
            const luxuryStyle = recolorOpenFreeMapDarkLuxury(rawStyle);

            map = new maplibregl.Map({
                container: document.getElementById('compose-map-container'),
                style: luxuryStyle,
                center: initCenter,
                zoom: initZoom,
                attributionControl: false,
                interactive: true,
                fadeDuration: 150
            });

            map.on('load', () => {
                mapLoaded = true;
                syncMapMarkers(getFilteredProducts(), true);
            });

            map.on('moveend', () => {
                if (!suppressNextMoveFilter && document.getElementById('map-bbox-toggle').checked) {
                    renderCardsAndMarkers(false);
                }
            });

            map.on('error', () => {
                if (!mapLoaded) activateFauxMapFallback();
            });
        } catch (err) {
            activateFauxMapFallback();
        }
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
                renderAll(true);
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
                    renderAll(true);
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
        const bounds = (useBbox && map && mapLoaded) ? map.getBounds() : null;

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
                if (!bounds.contains([lng, lat])) return false;
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
        renderAll(false);
    }
    window.toggleSelectProduct = toggleSelectProduct;

    function syncPinStates() {
        const leg = currentLeg();
        const selectedCodes = Object.values(leg.selected).filter(Boolean);
        for (const [code, marker] of markersByCode.entries()) {
            const el = marker.getElement();
            const isSelected = selectedCodes.includes(code);
            el.classList.toggle('msc-pin--active', isSelected);
            el.classList.toggle('msc-pin--hover', code === hoveredCode && !isSelected);
        }
        if (mapFauxMode) {
            renderFauxPins(getFilteredProducts());
        }
    }

    function highlightPin(code, active) {
        hoveredCode = active ? code : null;
        syncPinStates();
    }

    function syncMapMarkers(filtered, shouldFitBounds = false) {
        if (mapFauxMode) {
            renderFauxPins(filtered);
            return;
        }
        if (!map || !mapLoaded) return;

        const nextCodes = new Set(filtered.map(p => p.code));
        for (const [code, marker] of markersByCode.entries()) {
            if (!nextCodes.has(code)) {
                marker.remove();
                markersByCode.delete(code);
            }
        }

        filtered.forEach((p) => {
            if (!markersByCode.has(p.code)) {
                const el = document.createElement('div');
                el.className = 'msc-pin';
                el.textContent = '€' + Number(p.price).toLocaleString('it-IT');
                el.addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    window.location.href = `/prodotto/${p.slug}`;
                });
                el.addEventListener('mouseenter', () => {
                    hoveredCode = p.code;
                    syncPinStates();
                    const cardEl = document.querySelector(`.compose-card[data-code="${p.code}"]`);
                    if (cardEl) cardEl.classList.add('hovered');
                });
                el.addEventListener('mouseleave', () => {
                    hoveredCode = null;
                    syncPinStates();
                    const cardEl = document.querySelector(`.compose-card[data-code="${p.code}"]`);
                    if (cardEl) cardEl.classList.remove('hovered');
                });

                const marker = new maplibregl.Marker({ element: el, anchor: 'bottom', offset: [0, -4] })
                    .setLngLat([parseFloat(p.lng), parseFloat(p.lat)])
                    .addTo(map);
                markersByCode.set(p.code, marker);
            }
        });

        syncPinStates();

        if (shouldFitBounds && !document.getElementById('map-bbox-toggle').checked) {
            fitMapToProducts(filtered);
        }
    }

    function renderCardsAndMarkers(shouldFitBounds = false) {
        const leg = currentLeg();
        const cat = dossierState.activeCategory;
        const filtered = getFilteredProducts();

        document.getElementById('compose-heading-title').textContent = CAT_META[cat].title;
        document.getElementById('compose-results-counter').textContent = `${filtered.length} offerte disponibili`;

        const container = document.getElementById('compose-cards-container');
        container.innerHTML = '';

        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="grid-column:1/-1;padding:42px;text-align:center;background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:16px;">
                    <div class="serif" style="font-size:26px;color:var(--gold-bright);margin-bottom:8px;">Nessuna offerta nei limiti attuali</div>
                    <p style="font-size:13.5px;color:var(--text-sand);margin-bottom:18px;">Prova ad ampliare i filtri o disattivare il filtro "Cerca mentre muovi la mappa".</p>
                    <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('btn-reset-filters').click()">Ripristina tutti i filtri</button>
                </div>
            `;
            syncMapMarkers([], shouldFitBounds);
            return;
        }

        const selectedCode = leg.selected[cat];
        const mult = (cat === 'jet') ? 1 : leg.days;

        filtered.forEach((p) => {
            const isSelected = (selectedCode === p.code);
            const totalForStay = p.price * mult;
            const img = (p.images && p.images[0]) ? p.images[0] : '/images/hero.jpg';

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
        });

        syncMapMarkers(filtered, shouldFitBounds);
    }

    function setActiveCategory(cat) {
        dossierState.activeCategory = cat;
        saveDossierState();
        populateSubtypeOptions();
        renderAll(true);
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

    function renderAll(shouldFitBounds = false) {
        renderNotchBar();
        renderCardsAndMarkers(shouldFitBounds);
    }

    // Event Listeners
    document.getElementById('leg-dest-select').addEventListener('change', (e) => {
        currentLeg().destination = e.target.value;
        document.getElementById('map-bbox-toggle').checked = false;
        saveDossierState();
        flyToDestination(e.target.value);
        renderAll(true);
    });

    document.getElementById('leg-days-select').addEventListener('change', (e) => {
        currentLeg().days = parseInt(e.target.value, 10) || 4;
        saveDossierState();
        renderAll(false);
    });

    document.getElementById('leg-adults-select').addEventListener('change', (e) => {
        currentLeg().adults = parseInt(e.target.value, 10) || 2;
        saveDossierState();
        renderAll(false);
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
        renderAll(true);
    });

    ['filter-sort', 'filter-subtype', 'filter-max-price', 'filter-min-cap', 'map-bbox-toggle'].forEach((id) => {
        document.getElementById(id).addEventListener('change', () => renderCardsAndMarkers(true));
    });
    document.getElementById('filter-search').addEventListener('input', () => renderCardsAndMarkers(true));

    document.getElementById('btn-reset-filters').addEventListener('click', () => {
        document.getElementById('filter-sort').value = 'featured';
        document.getElementById('filter-subtype').value = 'all';
        document.getElementById('filter-max-price').value = '999999';
        document.getElementById('filter-min-cap').value = '1';
        document.getElementById('filter-search').value = '';
        document.getElementById('map-bbox-toggle').checked = false;
        renderCardsAndMarkers(true);
    });

    document.getElementById('btn-map-fit-bounds').addEventListener('click', () => {
        fitMapToProducts(getFilteredProducts());
    });
    document.getElementById('btn-map-zoom-in').addEventListener('click', () => {
        if (map && mapLoaded) map.zoomIn();
    });
    document.getElementById('btn-map-zoom-out').addEventListener('click', () => {
        if (map && mapLoaded) map.zoomOut();
    });

    // Initial boot
    syncControlsFromLeg();
    populateSubtypeOptions();
    renderAll(false);
    initMapLibre();
</script>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
