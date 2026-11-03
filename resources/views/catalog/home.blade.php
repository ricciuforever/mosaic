<?php require resource_path('views/layouts/header.blade.php'); ?>

<style>
    .hero-section {
        position: relative;
        min-height: 640px;
        display: flex;
        align-items: center;
        padding: 72px 0 84px;
        overflow: hidden;
        border-bottom: 1px solid var(--border-subtle);
    }
    .hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(7,10,15,0.58) 0%, rgba(7,10,15,0.86) 65%, #070a0f 100%),
                    url('/images/hero.jpg') center/cover no-repeat;
        z-index: 0;
        transform: scale(1.02);
    }
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 980px;
        margin: 0 auto;
        text-align: center;
    }
    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--gold-border);
        background: rgba(200, 155, 82, 0.12);
        color: var(--gold-bright);
        font-size: 11.5px;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 22px;
    }
    .hero-title {
        font-family: var(--font-serif);
        font-size: clamp(38px, 5.5vw, 68px);
        font-weight: 500;
        line-height: 1.08;
        color: var(--text-ivory);
        margin-bottom: 18px;
    }
    .hero-subtitle {
        font-size: clamp(15px, 1.8vw, 18px);
        color: var(--text-sand);
        max-width: 700px;
        margin: 0 auto 34px;
        font-weight: 400;
    }

    /* Mode Switch Tabs */
    .mode-tabs {
        display: inline-flex;
        background: rgba(17, 23, 35, 0.85);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-pill);
        padding: 5px;
        margin-bottom: 22px;
        gap: 4px;
    }
    .mode-tab {
        padding: 10px 22px;
        border-radius: var(--radius-pill);
        font-size: 13px;
        font-weight: 600;
        color: var(--text-sand);
        transition: all 0.2s;
    }
    .mode-tab.active {
        background: linear-gradient(135deg, #dfba73 0%, #c49347 100%);
        color: #080b11;
        box-shadow: 0 4px 16px rgba(200, 155, 82, 0.3);
    }

    /* AI Prompt Box & Manual Selector Box */
    .composer-shell {
        background: rgba(17, 23, 35, 0.92);
        backdrop-filter: blur(22px);
        border: 1px solid var(--gold-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.75), 0 0 40px rgba(200, 155, 82, 0.14);
        text-align: left;
    }
    .ai-prompt-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(7, 10, 15, 0.78);
        border: 1px solid rgba(200, 155, 82, 0.45);
        border-radius: 16px;
        padding: 10px 12px 10px 18px;
        transition: box-shadow 0.25s;
    }
    .ai-prompt-bar:focus-within {
        box-shadow: 0 0 0 3px rgba(200, 155, 82, 0.24);
        border-color: var(--gold-bright);
    }
    .ai-prompt-input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-ivory);
        font-size: 15px;
        outline: none;
    }
    .suggestion-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }
    .suggestion-pill {
        padding: 6px 13px;
        border-radius: var(--radius-pill);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-subtle);
        font-size: 12px;
        color: var(--text-sand);
        transition: all 0.2s;
    }
    .suggestion-pill:hover {
        border-color: var(--gold);
        color: var(--gold-bright);
        background: var(--gold-muted);
    }

    /* Manual Selector Grid */
    .manual-grid {
        display: grid;
        grid-template-columns: 1.5fr 1.1fr 1fr 0.9fr auto;
        gap: 14px;
        align-items: end;
    }
    .cat-check-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .cat-pill-toggle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 13px;
        border-radius: 12px;
        border: 1px solid var(--border-subtle);
        background: rgba(255, 255, 255, 0.03);
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-sand);
        cursor: pointer;
        user-select: none;
        transition: all 0.2s;
    }
    .cat-pill-toggle input { display: none; }
    .cat-pill-toggle.checked {
        border-color: var(--gold);
        background: var(--gold-muted);
        color: var(--gold-bright);
    }

    /* Product Cards */
    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 24px;
    }
    .lux-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .lux-card:hover {
        transform: translateY(-4px);
        border-color: var(--gold-border);
        box-shadow: 0 20px 44px rgba(0, 0, 0, 0.6);
    }
    .lux-card-media {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #0d131d;
    }
    .lux-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    .lux-card:hover .lux-card-media img {
        transform: scale(1.05);
    }
    .lux-card-badges {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        pointer-events: none;
    }
    .lux-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .lux-card-title {
        font-family: var(--font-serif);
        font-size: 23px;
        font-weight: 600;
        color: var(--text-ivory);
        margin-bottom: 4px;
    }
    .lux-card-sub {
        font-size: 12.5px;
        color: var(--text-sand);
        margin-bottom: 14px;
    }
    .lux-card-footer {
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid var(--border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filter-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .filter-pill {
        padding: 8px 16px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border-subtle);
        background: rgba(255, 255, 255, 0.03);
        color: var(--text-sand);
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .filter-pill.active, .filter-pill:hover {
        border-color: var(--gold);
        background: var(--gold-muted);
        color: var(--gold-bright);
    }

    @media (max-width: 980px) {
        .manual-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="container hero-content">
        <div class="hero-eyebrow">
            <span>✦</span>
            <span>Private Concierge · Ville · Supercar · Yacht · Jet Privati</span>
        </div>
        <h1 class="hero-title">L'arte di vivere l'Italia,<br><em>composta intorno a te.</em></h1>
        <p class="hero-subtitle">
            Combina in un unico dossier riservato dimore pieds-dans-l'eau, supercar consegnate sottobordo, yacht con equipaggio e trasferimenti in jet o elicottero VIP.
        </p>

        <!-- Mode Selector -->
        <div class="mode-tabs" role="tablist">
            <button type="button" class="mode-tab active" id="tab-btn-ai" onclick="switchComposerMode('ai')">
                ✦ Descrivi la tua esperienza (AI)
            </button>
            <button type="button" class="mode-tab" id="tab-btn-manual" onclick="switchComposerMode('manual')">
                ☰ Scegli manualmente
            </button>
        </div>

        <!-- Composer Shell -->
        <div class="composer-shell">
            <!-- AI MODE PANEL -->
            <div id="panel-mode-ai">
                <form id="home-ai-form">
                    <div class="ai-prompt-bar">
                        <span style="color:var(--gold-bright);font-size:20px;">✦</span>
                        <input
                            type="text"
                            id="home-ai-input"
                            class="ai-prompt-input"
                            placeholder="Es: 5 notti in Costa Smeralda per 6 ospiti con villa vista mare, Rolls-Royce, yacht Riva ed elicottero..."
                            autocomplete="off"
                        >
                        <button type="submit" class="btn btn-gold" id="home-ai-submit">
                            <span>Componi con AI</span>
                            <span>→</span>
                        </button>
                    </div>
                </form>
                <div class="suggestion-pills">
                    <button type="button" class="suggestion-pill" onclick="useAiSuggestion('5 notti in Costa Smeralda per 6 ospiti con villa sul mare, Rolls-Royce Cullinan, yacht Ferretti ed elicottero VIP')">
                        Costa Smeralda · Villa + Rolls-Royce + Yacht + Heli
                    </button>
                    <button type="button" class="suggestion-pill" onclick="useAiSuggestion('4 notti in Costiera Amalfitana tra Positano e Ravello per 4 persone con villa panoramica, Ferrari Spider e gozzo per Capri')">
                        Costiera Amalfitana · Positano, Ferrari & Capri
                    </button>
                    <button type="button" class="suggestion-pill" onclick="useAiSuggestion('Weekend lungo sul Lago di Como per una coppia con villa storica a Bellagio, Aston Martin e motoscafo Riva Aquarama + Jet da Milano')">
                        Lago di Como · Villa Storica + Riva Aquarama + Jet
                    </button>
                </div>

                <!-- Dynamic AI Result Preview Box -->
                <div id="home-ai-result" style="display:none;margin-top:20px;padding:20px;border-radius:16px;background:rgba(200,155,82,0.09);border:1px solid var(--gold-border);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:14px;">
                        <div>
                            <span class="badge badge-gold" id="home-ai-engine-badge">Itinerario Orchestrato</span>
                            <h3 class="serif" style="font-size:24px;color:var(--text-ivory);margin-top:6px;" id="home-ai-title">Proposta Su Misura</h3>
                        </div>
                        <a href="/compose" id="home-ai-compose-btn" class="btn btn-gold">
                            Personalizza nel Compositore Interattivo →
                        </a>
                    </div>
                    <p id="home-ai-reply" style="font-size:14px;color:var(--text-ivory);margin-bottom:16px;"></p>
                    <div id="home-ai-cards" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(210px, 1fr));gap:12px;"></div>
                </div>
            </div>

            <!-- MANUAL MODE PANEL -->
            <div id="panel-mode-manual" style="display:none;">
                <form action="/compose" method="GET" id="home-manual-form">
                    <input type="hidden" name="cats" id="manual-cats-input" value="villa,auto,yacht,jet">
                    <div class="manual-grid">
                        <div>
                            <label class="form-label">Esperienze da includere (Multi-Categoria)</label>
                            <div class="cat-check-group">
                                <label class="cat-pill-toggle checked" data-cat="villa">
                                    <input type="checkbox" checked value="villa"> 🏛️ Ville
                                </label>
                                <label class="cat-pill-toggle checked" data-cat="auto">
                                    <input type="checkbox" checked value="auto"> 🏎️ Auto
                                </label>
                                <label class="cat-pill-toggle checked" data-cat="yacht">
                                    <input type="checkbox" checked value="yacht"> 🛥️ Barche
                                </label>
                                <label class="cat-pill-toggle checked" data-cat="jet">
                                    <input type="checkbox" checked value="jet"> ✈️ Jet & Heli
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="manual-dest">Destinazione</label>
                            <select name="dest" id="manual-dest" class="form-select">
                                <?php foreach ($destinations as $d): ?>
                                    <option value="<?= e($d['slug']) ?>"><?= e($d['name']) ?> (<?= e($d['region']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="manual-days">Durata Soggiorno</label>
                            <select name="days" id="manual-days" class="form-select">
                                <option value="3">Weekend · 3 notti (15–18 Lug)</option>
                                <option value="4" selected>4 notti (15–19 Lug)</option>
                                <option value="5">5 notti (15–20 Lug)</option>
                                <option value="7">Settimana · 7 notti (15–22 Lug)</option>
                                <option value="10">10 notti (15–25 Lug)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="manual-adults">Ospiti</label>
                            <select name="adults" id="manual-adults" class="form-select">
                                <option value="2">2 Adulti (Coppia)</option>
                                <option value="4" selected>4 Adulti</option>
                                <option value="6">6 Adulti</option>
                                <option value="8">8 Adulti</option>
                                <option value="10">10+ Ospiti VIP</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-gold" style="width:100%;height:44px;">
                                Componi →
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- DESTINATIONS SHOWCASE -->
<section style="padding:72px 0 36px;">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:32px;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:11.5px;text-transform:uppercase;letter-spacing:0.15em;color:var(--gold-bright);margin-bottom:6px;">Tre Icone Mediterranee</div>
                <h2 class="serif" style="font-size:38px;font-weight:500;">Destinazioni d'Eccellenza</h2>
            </div>
            <a href="/compose" class="btn btn-outline btn-sm">Esplora su Mappa Interattiva →</a>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(340px, 1fr));gap:24px;">
            <?php foreach ($destinations as $dest): ?>
                <a href="/compose?dest=<?= urlencode($dest['slug']) ?>" class="lux-card" style="position:relative;min-height:290px;justify-content:flex-end;">
                    <img src="<?= e($dest['hero_image']) ?>" alt="<?= e($dest['name']) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;">
                    <div style="position:absolute;inset:0;background:linear-gradient(180deg, rgba(7,10,15,0.15) 0%, rgba(7,10,15,0.92) 100%);z-index:1;"></div>
                    <div style="position:relative;z-index:2;padding:26px;">
                        <span class="badge badge-gold" style="margin-bottom:10px;"><?= e($dest['region']) ?></span>
                        <h3 class="serif" style="font-size:30px;font-weight:600;margin-bottom:6px;"><?= e($dest['name']) ?></h3>
                        <p style="font-size:13px;color:var(--text-sand);margin-bottom:14px;"><?= e($dest['subtitle']) ?></p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            <?php foreach (array_slice($dest['locations'], 0, 5) as $loc): ?>
                                <span style="font-size:11px;padding:3px 9px;border-radius:99px;background:rgba(255,255,255,0.08);color:var(--text-ivory);">
                                    <?= e($loc['name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- IN PRIMO PIANO (FEATURED SHOWCASE) -->
<section style="padding:44px 0;">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:28px;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:11.5px;text-transform:uppercase;letter-spacing:0.15em;color:var(--gold-bright);margin-bottom:6px;">Selezione Curata dal Private Desk</div>
                <h2 class="serif" style="font-size:36px;font-weight:500;">In primo piano</h2>
            </div>
            <span style="font-size:13px;color:var(--text-muted);">Disponibilità verificata in tempo reale con i nostri Partner Gold</span>
        </div>

        <div class="catalog-grid">
            <?php foreach (array_slice($featured, 0, 6) as $item):
                $catLabels = ['villa' => 'Villa', 'auto' => 'Supercar', 'yacht' => 'Yacht', 'jet' => 'Jet & Heli'];
            ?>
                <article class="lux-card">
                    <a href="/prodotto/<?= e($item['slug']) ?>" class="lux-card-media">
                        <img src="<?= e($item['images'][0] ?? '/images/hero.jpg') ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                        <div class="lux-card-badges">
                            <span class="badge badge-gold"><?= e($catLabels[$item['category']] ?? $item['category']) ?></span>
                            <?php if (!empty($item['badge'])): ?>
                                <span class="badge badge-green"><?= e($item['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="lux-card-body">
                        <div style="font-size:11.5px;color:var(--gold-bright);margin-bottom:3px;"><?= e($item['location']) ?></div>
                        <a href="/prodotto/<?= e($item['slug']) ?>" class="lux-card-title"><?= e($item['title']) ?></a>
                        <div class="lux-card-sub"><?= e($item['subtitle']) ?></div>
                        <div class="lux-card-footer">
                            <div>
                                <span style="font-size:19px;font-weight:700;color:var(--text-ivory);"><?= format_eur($item['price']) ?></span>
                                <span style="font-size:12px;color:var(--text-muted);">/ <?= e($item['unit']) ?></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <a href="/prodotto/<?= e($item['slug']) ?>" class="btn btn-ghost btn-sm">Dettagli</a>
                                <a href="/compose?dest=<?= urlencode($item['destination_slug']) ?>&cats=<?= urlencode($item['category']) ?>&preselect=<?= urlencode($item['code']) ?>" class="btn btn-outline btn-sm">
                                    Componi +
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- LASCIATI ISPIRARE (FULL FILTERABLE 36-PRODUCT CATALOG) -->
<section style="padding:48px 0 32px;" id="catalogo-completo">
    <div class="container">
        <div style="margin-bottom:28px;">
            <div style="font-size:11.5px;text-transform:uppercase;letter-spacing:0.15em;color:var(--gold-bright);margin-bottom:6px;">Collezione Completa · <?= count($products) ?> Esperienze</div>
            <h2 class="serif" style="font-size:36px;font-weight:500;margin-bottom:20px;">Lasciati ispirare</h2>

            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;">
                <div class="filter-pills" id="home-cat-filters">
                    <button type="button" class="filter-pill active" data-filter-cat="all">Tutte le categorie (<?= count($products) ?>)</button>
                    <button type="button" class="filter-pill" data-filter-cat="villa">🏛️ Ville (10)</button>
                    <button type="button" class="filter-pill" data-filter-cat="auto">🏎️ Auto & Supercar (10)</button>
                    <button type="button" class="filter-pill" data-filter-cat="yacht">🛥️ Barche & Yacht (10)</button>
                    <button type="button" class="filter-pill" data-filter-cat="jet">✈️ Jet & Elicotteri VIP (6)</button>
                </div>

                <div class="filter-pills" id="home-dest-filters">
                    <button type="button" class="filter-pill active" data-filter-dest="all">Tutte le destinazioni</button>
                    <button type="button" class="filter-pill" data-filter-dest="costa-smeralda">Costa Smeralda</button>
                    <button type="button" class="filter-pill" data-filter-dest="costiera-amalfitana">Costiera Amalfitana</button>
                    <button type="button" class="filter-pill" data-filter-dest="lago-di-como">Lago di Como</button>
                </div>
            </div>
        </div>

        <div class="catalog-grid" id="home-catalog-grid">
            <?php foreach ($products as $item):
                $catLabels = ['villa' => 'Villa', 'auto' => 'Auto', 'yacht' => 'Yacht', 'jet' => 'Jet & Heli'];
            ?>
                <article class="lux-card home-product-item" data-category="<?= e($item['category']) ?>" data-destination="<?= e($item['destination_slug']) ?>">
                    <a href="/prodotto/<?= e($item['slug']) ?>" class="lux-card-media">
                        <img src="<?= e($item['images'][0] ?? '/images/hero.jpg') ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                        <div class="lux-card-badges">
                            <span class="badge badge-gold"><?= e($catLabels[$item['category']] ?? $item['category']) ?> · <?= e($item['subtype'] ?? '') ?></span>
                            <?php if (!empty($item['badge'])): ?>
                                <span class="badge badge-green"><?= e($item['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div class="lux-card-body">
                        <div style="display:flex;justify-content:space-between;font-size:11.5px;color:var(--gold-bright);margin-bottom:4px;">
                            <span><?= e($item['location']) ?></span>
                            <span>✓ <?= e($item['supplier_company'] ?? 'Partner Gold') ?></span>
                        </div>
                        <a href="/prodotto/<?= e($item['slug']) ?>" class="lux-card-title"><?= e($item['title']) ?></a>
                        <div class="lux-card-sub"><?= e($item['subtitle']) ?></div>
                        <div class="lux-card-footer">
                            <div>
                                <span style="font-size:18px;font-weight:700;color:var(--text-ivory);"><?= format_eur($item['price']) ?></span>
                                <span style="font-size:12px;color:var(--text-muted);">/ <?= e($item['unit']) ?></span>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <a href="/prodotto/<?= e($item['slug']) ?>" class="btn btn-ghost btn-sm">Scheda</a>
                                <a href="/compose?dest=<?= urlencode($item['destination_slug']) ?>&cats=<?= urlencode($item['category']) ?>&preselect=<?= urlencode($item['code']) ?>" class="btn btn-gold btn-sm">
                                    Seleziona
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    function switchComposerMode(mode) {
        const aiBtn = document.getElementById('tab-btn-ai');
        const manBtn = document.getElementById('tab-btn-manual');
        const aiPanel = document.getElementById('panel-mode-ai');
        const manPanel = document.getElementById('panel-mode-manual');
        if (mode === 'ai') {
            aiBtn.classList.add('active');
            manBtn.classList.remove('active');
            aiPanel.style.display = 'block';
            manPanel.style.display = 'none';
        } else {
            manBtn.classList.add('active');
            aiBtn.classList.remove('active');
            manPanel.style.display = 'block';
            aiPanel.style.display = 'none';
        }
    }

    function useAiSuggestion(text) {
        const input = document.getElementById('home-ai-input');
        input.value = text;
        document.getElementById('home-ai-form').requestSubmit();
    }

    // Manual category pills toggle
    document.querySelectorAll('.cat-pill-toggle').forEach((label) => {
        label.addEventListener('click', (e) => {
            e.preventDefault();
            label.classList.toggle('checked');
            const selected = Array.from(document.querySelectorAll('.cat-pill-toggle.checked')).map(el => el.dataset.cat);
            if (selected.length === 0) {
                label.classList.add('checked');
                return;
            }
            document.getElementById('manual-cats-input').value = selected.join(',');
        });
    });

    // Home AI Form Orchestration
    document.getElementById('home-ai-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('home-ai-input');
        const btn = document.getElementById('home-ai-submit');
        const box = document.getElementById('home-ai-result');
        const prompt = input.value.trim();
        if (!prompt) return;

        btn.disabled = true;
        btn.innerHTML = '<span>Orchestrazione AI...</span>';

        try {
            const res = await fetch('/api/ai/concierge', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ prompt })
            });
            const data = await res.json();
            box.style.display = 'block';
            document.getElementById('home-ai-engine-badge').textContent = `AI Engine: ${data.engine} · Totale Stimato €${Number(data.total_estimated || 0).toLocaleString('it-IT')}`;
            document.getElementById('home-ai-title').textContent = `Dossier ${data.intent?.destination_name || 'Costa Smeralda'} (${data.intent?.days || 4} notti · ${data.intent?.adults || 2} ospiti)`;
            document.getElementById('home-ai-reply').textContent = data.reply || '';
            document.getElementById('home-ai-compose-btn').href = data.compose_url || '/compose';

            const cardsContainer = document.getElementById('home-ai-cards');
            cardsContainer.innerHTML = '';
            (data.recommendations || []).forEach((rec) => {
                const c = document.createElement('div');
                c.style.background = 'rgba(11,15,23,0.9)';
                c.style.border = '1px solid var(--border-subtle)';
                c.style.borderRadius = '12px';
                c.style.padding = '12px';
                c.innerHTML = `
                    <div style="font-size:10px;text-transform:uppercase;color:var(--gold-bright);letter-spacing:0.08em;margin-bottom:4px;">${rec.category} · ${rec.days} ${rec.unit}</div>
                    <div class="serif" style="font-size:18px;font-weight:600;margin-bottom:3px;">${rec.title}</div>
                    <div style="font-size:11.5px;color:var(--text-sand);margin-bottom:8px;">${rec.location}</div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <strong>€${Number(rec.subtotal).toLocaleString('it-IT')}</strong>
                        <a href="${rec.url}" style="color:var(--gold-bright);font-size:11.5px;">Vedi scheda →</a>
                    </div>
                `;
                cardsContainer.appendChild(c);
            });
        } catch (err) {
            window.location.href = '/compose';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span>Componi con AI</span><span>→</span>';
        }
    });

    // Filterable Inspiration Grid
    (function() {
        let activeCat = 'all';
        let activeDest = 'all';
        const items = document.querySelectorAll('.home-product-item');

        function applyGridFilters() {
            items.forEach((card) => {
                const matchCat = (activeCat === 'all' || card.dataset.category === activeCat);
                const matchDest = (activeDest === 'all' || card.dataset.destination === activeDest);
                card.style.display = (matchCat && matchDest) ? 'flex' : 'none';
            });
        }

        document.querySelectorAll('#home-cat-filters .filter-pill').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#home-cat-filters .filter-pill').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeCat = btn.dataset.filterCat;
                applyGridFilters();
            });
        });

        document.querySelectorAll('#home-dest-filters .filter-pill').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#home-dest-filters .filter-pill').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeDest = btn.dataset.filterDest;
                applyGridFilters();
            });
        });
    })();
</script>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
