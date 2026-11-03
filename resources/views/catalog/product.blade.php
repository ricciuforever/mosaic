<?php
require resource_path('views/layouts/header.blade.php');

$catNames = ['villa' => 'Ville & Dimore', 'auto' => 'Supercar & SUV', 'yacht' => 'Barche & Yacht', 'jet' => 'Jet & Elicotteri VIP'];
$catName = $catNames[$product['category']] ?? ucfirst($product['category']);
$images = $product['images'] ?: ['/images/hero.jpg', '/images/hero.jpg', '/images/hero.jpg', '/images/hero.jpg'];
$extra = $product['extra'] ?? [];
?>

<style>
    .bento-gallery {
        display: grid;
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 230px 230px;
        gap: 14px;
        border-radius: var(--radius-lg);
        overflow: hidden;
        position: relative;
        margin-bottom: 36px;
        border: 1px solid var(--border-subtle);
    }
    .bento-main {
        grid-row: 1 / 3;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .bento-side {
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .bento-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .bento-gallery div:hover img {
        transform: scale(1.03);
    }
    .bento-btn-all {
        position: absolute;
        right: 18px;
        bottom: 18px;
        z-index: 5;
        background: rgba(11, 15, 23, 0.92);
        backdrop-filter: blur(10px);
        border: 1px solid var(--gold);
        color: var(--gold-bright);
        padding: 9px 18px;
        border-radius: var(--radius-pill);
        font-size: 12.5px;
        font-weight: 600;
    }

    .product-layout {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 42px;
        align-items: start;
    }
    .detail-section {
        padding: 28px 0;
        border-bottom: 1px solid var(--border-subtle);
    }
    .detail-section-title {
        font-family: var(--font-serif);
        font-size: 27px;
        font-weight: 600;
        color: var(--text-ivory);
        margin-bottom: 16px;
    }
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }
    .spec-item {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 12px;
    }
    .amenity-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-subtle);
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sticky-booking-box {
        position: sticky;
        top: 100px;
        background: var(--bg-card);
        border: 1px solid var(--gold-border);
        border-radius: var(--radius-lg);
        padding: 26px;
        box-shadow: var(--shadow-luxury);
    }

    @media (max-width: 980px) {
        .bento-gallery { grid-template-columns: 1fr; grid-template-rows: 300px; }
        .bento-side { display: none; }
        .product-layout { grid-template-columns: 1fr; }
        .specs-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="container" style="padding-top:28px;padding-bottom:60px;">
    <!-- Breadcrumb & Back to Composer -->
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;font-size:13px;color:var(--text-sand);">
        <div>
            <a href="/" style="color:var(--text-muted);">Home</a>
            <span style="margin:0 8px;">/</span>
            <a href="/compose?dest=<?= urlencode($product['destination_slug']) ?>" style="color:var(--text-muted);"><?= e($product['destination_name'] ?? $product['destination_slug']) ?></a>
            <span style="margin:0 8px;">/</span>
            <a href="/compose?dest=<?= urlencode($product['destination_slug']) ?>&cats=<?= urlencode($product['category']) ?>" style="color:var(--text-muted);"><?= e($catName) ?></a>
            <span style="margin:0 8px;">/</span>
            <span style="color:var(--gold-bright);"><?= e($product['title']) ?></span>
        </div>
        <a href="/compose?dest=<?= urlencode($product['destination_slug']) ?>&cats=<?= urlencode($product['category']) ?>" class="btn btn-ghost btn-sm">
            ← Torna al Compositore
        </a>
    </div>

    <!-- Header Title & Verified Supplier Badge -->
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:22px;">
        <div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
                <span class="badge badge-gold"><?= e($catName) ?></span>
                <?php if (!empty($product['subtype'])): ?>
                    <span class="badge badge-blue"><?= e($product['subtype']) ?></span>
                <?php endif; ?>
                <?php if (!empty($product['badge'])): ?>
                    <span class="badge badge-green"><?= e($product['badge']) ?></span>
                <?php endif; ?>
            </div>
            <h1 class="serif" style="font-size:clamp(32px, 4vw, 48px);font-weight:600;line-height:1.1;margin-bottom:6px;">
                <?= e($product['title']) ?>
            </h1>
            <p style="font-size:15px;color:var(--text-sand);display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                <span>📍 <?= e($product['location']) ?> · <?= e($product['subtitle']) ?></span>
                <?php if (!empty($extra['rating'])): ?>
                    <span style="color:var(--gold-bright);font-weight:600;">
                        ★ <?= number_format((float)$extra['rating'], 1, ',', '.') ?>
                        <?php if (!empty($extra['reviewsCount'])): ?>
                            <span style="color:var(--text-muted);font-weight:400;">(<?= (int)$extra['reviewsCount'] ?> recensioni)</span>
                        <?php endif; ?>
                    </span>
                <?php endif; ?>
            </p>
        </div>

        <div style="background:rgba(200,155,82,0.08);border:1px solid var(--gold-border);border-radius:14px;padding:10px 16px;display:flex;align-items:center;gap:12px;">
            <span style="font-size:20px;">🛡️</span>
            <div>
                <div style="font-size:11px;text-transform:uppercase;color:var(--gold-bright);letter-spacing:0.06em;">
                    <?= e($product['supplier_badge'] ?? 'Fornitore Verificato Gold') ?>
                </div>
                <div style="font-size:13.5px;font-weight:600;color:var(--text-ivory);">
                    <?= e($product['supplier_company'] ?? 'Mosaic Partner') ?> · ★ <?= e($product['supplier_rating'] ?? '4.98') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-PHOTO BENTO HERO GALLERY -->
    <div class="bento-gallery">
        <div class="bento-main" onclick="document.getElementById('photo-lightbox').showModal()">
            <img src="<?= e($images[0]) ?>" alt="<?= e($product['title']) ?> - Vista 1">
        </div>
        <div class="bento-side" onclick="document.getElementById('photo-lightbox').showModal()">
            <img src="<?= e($images[1] ?? $images[0]) ?>" alt="<?= e($product['title']) ?> - Vista 2">
        </div>
        <div class="bento-side" onclick="document.getElementById('photo-lightbox').showModal()">
            <img src="<?= e($images[2] ?? $images[0]) ?>" alt="<?= e($product['title']) ?> - Vista 3">
        </div>
        <button type="button" class="bento-btn-all" onclick="document.getElementById('photo-lightbox').showModal()">
            ✦ Mostra tutte le foto (<?= count($images) ?>)
        </button>
    </div>

    <!-- QUICK HIGHLIGHTS BAR (PROTOTYPE PILLS) -->
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;">
        <?php if ($product['category'] === 'auto'): ?>
            <?php if (!empty($extra['make'])): ?>
                <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Marca</span> <strong><?= e($extra['make']) ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($extra['model'])): ?>
                <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Modello</span> <strong><?= e($extra['model']) ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($extra['year'])): ?>
                <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Anno</span> <strong><?= (int)$extra['year'] ?></strong></span>
            <?php endif; ?>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Tipologia</span> <strong><?= e($extra['autoType'] ?? $product['subtype'] ?? 'SUV') ?></strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Posti</span> <strong><?= (int)($extra['seats'] ?? $product['capacity']) ?></strong></span>
        <?php elseif ($product['category'] === 'villa'): ?>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Camere</span> <strong><?= (int)($extra['bedrooms'] ?? 5) ?></strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Bagni</span> <strong><?= (int)($extra['bathrooms'] ?? 6) ?></strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Superficie</span> <strong><?= (int)($extra['sqm'] ?? 450) ?> m²</strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Ospiti max</span> <strong><?= (int)($extra['maxGuests'] ?? $product['capacity']) ?></strong></span>
        <?php elseif ($product['category'] === 'yacht'): ?>
            <?php if (!empty($extra['manufacturer'])): ?>
                <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Cantiere</span> <strong><?= e($extra['manufacturer']) ?></strong></span>
            <?php endif; ?>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Lunghezza</span> <strong><?= e($extra['lengthMeters'] ?? 20) ?> m</strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Cabine</span> <strong><?= (int)($extra['cabins'] ?? 3) ?></strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Ospiti</span> <strong><?= (int)($extra['maxGuests'] ?? $product['capacity']) ?></strong></span>
        <?php else: ?>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Classe</span> <strong><?= e($extra['aircraftType'] ?? $product['subtype']) ?></strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Autonomia</span> <strong><?= number_format((int)($extra['rangeKm'] ?? 3500), 0, ',', '.') ?> km</strong></span>
            <span class="amenity-pill" style="padding:8px 14px;"><span style="color:var(--text-muted);">Passeggeri</span> <strong><?= e($product['capacity_label']) ?></strong></span>
        <?php endif; ?>
    </div>

    <!-- MAIN 2-COLUMN PRODUCT CONTENT + STICKY BOOKING BOX -->
    <div class="product-layout">
        <div>
            <!-- Editorial Description -->
            <section class="detail-section" style="padding-top:0;">
                <h2 class="detail-section-title">L'Esperienza in Dettaglio</h2>
                <?php foreach (explode("\n\n", $product['description']) as $para): ?>
                    <p style="font-size:15.5px;color:var(--text-sand);line-height:1.75;margin-bottom:14px;">
                        <?= e(trim($para)) ?>
                    </p>
                <?php endforeach; ?>
            </section>

            <!-- Scheda Tecnica (2-Column Dynamic Specs) -->
            <section class="detail-section">
                <h2 class="detail-section-title">Scheda tecnica</h2>
                <div class="specs-grid">
                    <?php if ($product['category'] === 'auto'): ?>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Marca</span>
                            <strong><?= e($extra['make'] ?? explode(' ', $product['title'])[0]) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Modello</span>
                            <strong><?= e($extra['model'] ?? $product['title']) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Anno</span>
                            <strong><?= (int)($extra['year'] ?? 2024) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Tipologia</span>
                            <strong><?= e($extra['autoType'] ?? $product['subtype'] ?? 'SUV') ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Posti</span>
                            <strong><?= (int)($extra['seats'] ?? $product['capacity']) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Cambio</span>
                            <strong><?= e($extra['transmission'] ?? 'Automatico') ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Alimentazione</span>
                            <strong><?= e($extra['fuelType'] ?? 'Benzina') ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Potenza</span>
                            <strong><?= e(!empty($extra['horsepower']) ? ($extra['horsepower'] . ' CV') : ($extra['power'] ?? '571 CV')) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Km inclusi</span>
                            <strong><?= (int)($extra['kmIncluded'] ?? 200) ?> km/giorno</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Consegna</span>
                            <strong><?= !empty($extra['deliveryIncluded']) ? 'Inclusa' : 'Su richiesta' ?></strong>
                        </div>
                    <?php elseif ($product['category'] === 'villa'): ?>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Categoria</span>
                            <strong><?= e($catName) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Località Base</span>
                            <strong><?= e($product['location']) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Camere da letto</span>
                            <strong><?= (int)($extra['bedrooms'] ?? 5) ?> Suite</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Posti letto</span>
                            <strong><?= (int)($extra['beds'] ?? ($extra['bedrooms'] ?? 6)) ?> Letti</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Bagni</span>
                            <strong><?= (int)($extra['bathrooms'] ?? 6) ?> Bagni</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Superficie Interna</span>
                            <strong><?= (int)($extra['sqm'] ?? 450) ?> m²</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Ospiti Massimi</span>
                            <strong><?= (int)($extra['maxGuests'] ?? $product['capacity']) ?> ospiti</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Piscina & Mare</span>
                            <strong><?= !empty($extra['seaView']) ? 'Vista mare & Piscina' : 'Piscina Privata' ?></strong>
                        </div>
                    <?php elseif ($product['category'] === 'yacht'): ?>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Cantiere</span>
                            <strong><?= e($extra['manufacturer'] ?? explode(' ', $product['title'])[0]) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Tipologia</span>
                            <strong><?= e($extra['yachtType'] ?? $product['subtype'] ?? 'Motor yacht') ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Lunghezza Fuori Tutto</span>
                            <strong><?= e($extra['lengthMeters'] ?? 20) ?> metri</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Cabine Ospiti</span>
                            <strong><?= (int)($extra['cabins'] ?? 3) ?> Cabine</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Ospiti in Navigazione</span>
                            <strong><?= (int)($extra['maxGuests'] ?? $product['capacity']) ?> ospiti</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Velocità di Crociera</span>
                            <strong><?= (int)($extra['cruiseSpeedKnots'] ?? 30) ?> nodi</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Equipaggio & Skipper</span>
                            <strong><?= !empty($extra['skipperIncluded']) ? 'Comandante & Marinai Inclusi' : 'Skipper su richiesta' ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Marina di Base</span>
                            <strong><?= e($extra['marina'] ?? $product['location']) ?></strong>
                        </div>
                    <?php elseif ($product['category'] === 'jet'): ?>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Classe Velivolo</span>
                            <strong><?= e($extra['aircraftType'] ?? $product['subtype']) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Capienza Cabina</span>
                            <strong><?= e($product['capacity_label']) ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Autonomia Operativa</span>
                            <strong><?= number_format((int)($extra['rangeKm'] ?? 3500), 0, ',', '.') ?> km</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Velocità di Crociera</span>
                            <strong><?= (int)($extra['cruiseSpeedKmh'] ?? 800) ?> km/h</strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Equipaggio di Volo</span>
                            <strong><?= e($extra['cabinCrew'] ?? '2 Comandanti Senior') ?></strong>
                        </div>
                        <div class="spec-item">
                            <span style="color:var(--text-muted);font-size:13px;">Terminal / Base</span>
                            <strong><?= e($product['location']) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Cosa è incluso / Cosa è escluso -->
            <section class="detail-section">
                <h2 class="detail-section-title">Cosa è incluso</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;">
                    <div style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.25);border-radius:14px;padding:20px;">
                        <div style="font-size:12px;text-transform:uppercase;color:#6ee7b7;letter-spacing:0.08em;font-weight:700;margin-bottom:12px;">
                            ✓ Incluso nella tariffa
                        </div>
                        <ul style="list-style:none;display:grid;gap:9px;font-size:13.5px;color:var(--text-ivory);">
                            <?php foreach (($product['servicesIncluded'] ?: ['Accoglienza VIP dedicata', 'Assistenza Private Concierge 24/7']) as $inc): ?>
                                <li>✓ <?= e($inc) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div style="background:rgba(255,255,255,0.02);border:1px solid var(--border-subtle);border-radius:14px;padding:20px;">
                        <div style="font-size:12px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.08em;font-weight:700;margin-bottom:12px;">
                            ✦ Extra su richiesta / Esclusi
                        </div>
                        <ul style="list-style:none;display:grid;gap:9px;font-size:13.5px;color:var(--text-sand);">
                            <?php foreach (($product['servicesExcluded'] ?: ['Consumi carburante e ormeggi fuori base', 'Servizi chef o security aggiuntivi']) as $exc): ?>
                                <li>· <?= e($exc) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Dotazioni e Servizi -->
            <section class="detail-section">
                <h2 class="detail-section-title">Dotazioni e servizi</h2>
                <div class="amenities-grid">
                    <?php foreach (($product['amenities'] ?: ['Servizio Concierge 24/7']) as $am): ?>
                        <div class="amenity-pill">
                            <span style="color:var(--gold-bright);">✦</span>
                            <span><?= e($am) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Regole e Cancellazione -->
            <section class="detail-section">
                <h2 class="detail-section-title">Regole e cancellazione</h2>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;">
                    <div>
                        <h3 style="font-size:13px;text-transform:uppercase;color:var(--gold-bright);margin-bottom:10px;">Regole e Requisiti</h3>
                        <ul style="list-style:none;display:grid;gap:8px;font-size:13.5px;color:var(--text-sand);">
                            <?php foreach (($product['rules'] ?: ['Documento d\'identità richiesto al check-in']) as $rule): ?>
                                <li>• <?= e($rule) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size:13px;text-transform:uppercase;color:var(--gold-bright);margin-bottom:10px;">Politica di Cancellazione</h3>
                        <p style="font-size:13.5px;color:var(--text-sand);line-height:1.6;">
                            <?= e($product['cancellation_policy'] ?: 'Cancellazione gratuita fino a 14 giorni prima dell\'inizio del soggiorno.') ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Dove si trova (Mini-Map) -->
            <section class="detail-section" style="border-bottom:none;">
                <h2 class="detail-section-title">Dove si trova</h2>
                <p style="font-size:13.5px;color:var(--text-sand);margin-bottom:14px;">
                    📍 <?= e($product['location']) ?> · Coordinate verificate: <?= number_format($product['lat'], 4) ?>° N, <?= number_format($product['lng'], 4) ?>° E
                </p>
                <div id="product-mini-map" style="height:310px;border-radius:16px;border:1px solid var(--border-subtle);overflow:hidden;"></div>
            </section>
        </div>

        <!-- STICKY BOOKING / COMPOSITION SIDEBAR -->
        <aside class="sticky-booking-box">
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:18px;padding-bottom:16px;border-bottom:1px solid var(--border-subtle);">
                <div>
                    <span style="font-size:28px;font-weight:700;color:var(--text-ivory);"><?= format_eur($product['price']) ?></span>
                    <span style="font-size:13px;color:var(--text-muted);">/ <?= e($product['unit']) ?> · IVA inclusa</span>
                </div>
                <span class="badge <?= $product['is_available'] ? 'badge-green' : 'badge-red' ?>">
                    <?= $product['is_available'] ? 'Disponibile' : 'Su richiesta' ?>
                </span>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label" for="detail-qty-select">Durata / Quantità (<?= e($product['unit']) ?>)</label>
                <select id="detail-qty-select" class="form-select">
                    <?php
                    $defaultQty = ($product['category'] === 'jet') ? 1 : 3;
                    for ($q = 1; $q <= 14; $q++):
                    ?>
                        <option value="<?= $q ?>" <?= $q === $defaultQty ? 'selected' : '' ?>>
                            <?= $q ?> <?= e($product['unit']) ?> (<?= format_eur($product['price'] * $q) ?>)
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:12px;padding:14px;margin-bottom:18px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;color:var(--text-sand);">
                    <span><?= format_eur($product['price']) ?> × <span id="calc-qty-label"><?= $defaultQty ?></span> <?= e($product['unit']) ?></span>
                    <span id="calc-subtotal-label"><?= format_eur($product['price'] * $defaultQty) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;color:var(--text-sand);">
                    <span>Servizio concierge Mosaic</span>
                    <span style="color:#6ee7b7;">Incluso</span>
                </div>
                <?php if (!empty($extra['depositAmount'])): ?>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;color:var(--text-muted);">
                        <span>Cauzione (rimborsabile)</span>
                        <span><?= format_eur((int)$extra['depositAmount']) ?></span>
                    </div>
                <?php endif; ?>
                <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid var(--border-subtle);font-size:15px;font-weight:700;color:var(--gold-bright);">
                    <span>Totale stimato</span>
                    <span id="calc-total-label"><?= format_eur($product['price'] * $defaultQty) ?></span>
                </div>
            </div>

            <div style="display:grid;gap:10px;">
                <button type="button" class="btn btn-gold" id="btn-add-to-dossier" style="width:100%;">
                    ✦ Aggiungi alla composizione
                </button>
                <button type="button" class="btn btn-outline" id="btn-book-now-direct" style="width:100%;">
                    Richiedi subito questo elemento →
                </button>
            </div>

            <div style="margin-top:18px;padding-top:14px;border-top:1px solid var(--border-subtle);font-size:12px;color:var(--text-muted);line-height:1.5;">
                🛡️ Nessun addebito immediato. Il Private Concierge verifica la disponibilità oraria con <strong><?= e($product['supplier_company'] ?? 'il Partner') ?></strong> e ti invia un'offerta personalizzata.
            </div>
        </aside>
    </div>

    <!-- SIMILAR PRODUCTS ("Potrebbe interessarti anche") -->
    <?php if (!empty($similar)): ?>
        <section style="margin-top:64px;padding-top:40px;border-top:1px solid var(--border-subtle);">
            <h2 class="serif" style="font-size:32px;font-weight:500;margin-bottom:22px;">Potrebbe interessarti anche</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(310px, 1fr));gap:24px;">
                <?php foreach ($similar as $sim): ?>
                    <article style="background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;">
                        <a href="/prodotto/<?= e($sim['slug']) ?>" style="aspect-ratio:16/10;overflow:hidden;display:block;">
                            <img src="<?= e($sim['images'][0] ?? '/images/hero.jpg') ?>" alt="<?= e($sim['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
                        </a>
                        <div style="padding:18px;display:flex;flex-direction:column;flex:1;">
                            <div style="font-size:11.5px;color:var(--gold-bright);margin-bottom:3px;"><?= e($sim['location']) ?></div>
                            <a href="/prodotto/<?= e($sim['slug']) ?>" class="serif" style="font-size:22px;font-weight:600;margin-bottom:4px;"><?= e($sim['title']) ?></a>
                            <div style="font-size:12.5px;color:var(--text-sand);margin-bottom:14px;"><?= e($sim['subtitle']) ?></div>
                            <div style="margin-top:auto;display:flex;justify-content:space-between;align-items:center;">
                                <strong><?= format_eur($sim['price']) ?> <span style="font-size:11px;font-weight:400;color:var(--text-muted);">/ <?= e($sim['unit']) ?></span></strong>
                                <a href="/prodotto/<?= e($sim['slug']) ?>" class="btn btn-outline btn-sm">Esplora →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<!-- MODERN WEB GUIDANCE COMPLIANT <dialog closedby="any"> LIGHTBOX -->
<dialog id="photo-lightbox" class="luxury-dialog" closedby="any" aria-labelledby="lightbox-dialog-title" style="width:min(94vw, 1020px);">
    <div class="dialog-header">
        <div>
            <div class="serif" id="lightbox-dialog-title" style="font-size:22px;font-weight:600;color:var(--gold-bright);"><?= e($product['title']) ?></div>
            <div style="font-size:12px;color:var(--text-sand);"><?= e($product['location']) ?> · Galleria Fotografica Ufficiale</div>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('photo-lightbox').close()">Chiudi ✕</button>
    </div>
    <div class="dialog-body" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(380px, 1fr));gap:16px;">
        <?php foreach ($images as $idx => $imgUrl): ?>
            <img src="<?= e($imgUrl) ?>" alt="<?= e($product['title']) ?> - Foto <?= $idx + 1 ?>" style="width:100%;border-radius:12px;border:1px solid var(--border-subtle);">
        <?php endforeach; ?>
    </div>
</dialog>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    (function() {
        const unitPrice = <?= (int)$product['price'] ?>;
        const prodCode = <?= json_encode($product['code']) ?>;
        const prodCat = <?= json_encode($product['category']) ?>;
        const prodDest = <?= json_encode($product['destination_slug']) ?>;

        const qtySelect = document.getElementById('detail-qty-select');
        qtySelect.addEventListener('change', () => {
            const q = parseInt(qtySelect.value, 10) || 1;
            const tot = '€' + (unitPrice * q).toLocaleString('it-IT');
            document.getElementById('calc-qty-label').textContent = String(q);
            document.getElementById('calc-subtotal-label').textContent = tot;
            document.getElementById('calc-total-label').textContent = tot;
        });

        function saveProductIntoDossier() {
            const q = parseInt(qtySelect.value, 10) || 4;
            let state = null;
            try {
                state = JSON.parse(localStorage.getItem('mosaic_dossier_v2') || 'null');
            } catch (e) {}

            if (!state || !Array.isArray(state.legs) || state.legs.length === 0) {
                state = {
                    activeLegIndex: 0,
                    activeCategory: prodCat,
                    legs: [{
                        destination: prodDest,
                        days: q,
                        adults: 2,
                        children: 0,
                        startDate: '15 Lug 2026',
                        endDate: '19 Lug 2026',
                        selected: { villa: null, auto: null, yacht: null, jet: null }
                    }]
                };
            }

            let targetLeg = state.legs.find(l => l.destination === prodDest);
            if (!targetLeg) {
                targetLeg = state.legs[0];
                targetLeg.destination = prodDest;
            }
            targetLeg.selected[prodCat] = prodCode;
            if (prodCat !== 'jet') {
                targetLeg.days = q;
            }
            localStorage.setItem('mosaic_dossier_v2', JSON.stringify(state));
            if (typeof updateDossierCounter === 'function') updateDossierCounter();
        }

        document.getElementById('btn-add-to-dossier').addEventListener('click', () => {
            saveProductIntoDossier();
            window.location.href = `/compose?dest=${encodeURIComponent(prodDest)}&cats=${encodeURIComponent(prodCat)}&preselect=${encodeURIComponent(prodCode)}`;
        });

        document.getElementById('btn-book-now-direct').addEventListener('click', () => {
            saveProductIntoDossier();
            window.location.href = '/richiesta';
        });

        // Mini-Map initialization
        const lat = <?= (float)$product['lat'] ?>;
        const lng = <?= (float)$product['lng'] ?>;
        const miniMap = L.map('product-mini-map', { center: [lat, lng], zoom: 13 });
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd'
        }).addTo(miniMap);

        const pinIcon = L.divIcon({
            className: 'mosaic-pin-wrapper',
            html: `<div style="background:#e4be7a;color:#080b11;border:2px solid #fff;border-radius:99px;padding:5px 12px;font-weight:700;font-size:12px;white-space:nowrap;box-shadow:0 6px 18px rgba(0,0,0,0.8);">€${unitPrice.toLocaleString('it-IT')}</div>`,
            iconSize: [84, 32],
            iconAnchor: [42, 16]
        });
        L.marker([lat, lng], { icon: pinIcon }).addTo(miniMap);
    })();
</script>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
