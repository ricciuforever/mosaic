<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding-top:36px;padding-bottom:72px;">
    <?php if (!empty($savedBooking)): ?>
        <!-- SAVED BOOKING CONFIRMATION DOSSIER -->
        <div style="max-width:860px;margin:0 auto;background:var(--bg-card);border:1px solid var(--gold);border-radius:24px;padding:38px;box-shadow:var(--shadow-luxury);">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
                <span class="badge badge-green">✓ Richiesta Registrata a Sistema</span>
                <span class="badge badge-gold">Rif. Ufficiale: <?= e($savedBooking['reference_code']) ?></span>
            </div>
            <h1 class="serif" style="font-size:40px;font-weight:600;margin-bottom:10px;">
                Grazie, <?= e($savedBooking['client_first_name']) ?> <?= e($savedBooking['client_last_name']) ?>.
            </h1>
            <p style="font-size:15px;color:var(--text-sand);margin-bottom:28px;">
                Il tuo dossier multi-categoria è stato preso in carico dal nostro Private Concierge Desk. Riceverai entro 60 minuti la conferma oraria dei partner e la proposta riservata all'indirizzo <strong><?= e($savedBooking['client_email']) ?></strong>.
            </p>

            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:16px;padding:22px;margin-bottom:26px;">
                <h3 class="serif" style="font-size:22px;color:var(--gold-bright);margin-bottom:14px;">Riepilogo Esperienze Selezionate</h3>
                <div style="display:grid;gap:12px;">
                    <?php foreach (($savedBooking['items'] ?? []) as $it): ?>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid var(--border-subtle);font-size:14px;">
                            <div>
                                <span class="badge badge-gold" style="margin-right:8px;"><?= e($it['category']) ?></span>
                                <strong><?= e($it['product_title']) ?></strong>
                                <span style="color:var(--text-muted);font-size:12.5px;"> · <?= (int)$it['quantity_days'] ?> <?= e($it['unit_label']) ?></span>
                            </div>
                            <strong><?= format_eur($it['subtotal']) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:16px;font-size:18px;font-weight:700;color:var(--gold-bright);">
                    <span>Valore Stimato Dossier</span>
                    <span><?= format_eur($savedBooking['total_estimated']) ?></span>
                </div>
            </div>

            <div style="display:flex;gap:14px;flex-wrap:wrap;">
                <a href="/area-clienti" class="btn btn-gold">Vai all'Area Clienti VIP →</a>
                <a href="/compose" class="btn btn-outline">Componi un'altra esperienza</a>
                <a href="/" class="btn btn-ghost">Torna alla Home</a>
            </div>
        </div>
    <?php else: ?>
        <!-- INTERACTIVE CHECKOUT & CONCIERGE REQUEST PAGE -->
        <div style="margin-bottom:28px;">
            <div style="font-size:11.5px;text-transform:uppercase;letter-spacing:0.15em;color:var(--gold-bright);margin-bottom:6px;">Finalizzazione Dossier Riservato</div>
            <h1 class="serif" style="font-size:42px;font-weight:500;">Invia la tua richiesta al Private Concierge</h1>
            <p style="font-size:14.5px;color:var(--text-sand);">
                Controlla le tappe e le esperienze combinate nel tuo soggiorno. Il nostro team verificherà slot e disponibilità inviandoti un preventivo su misura.
            </p>
        </div>

        <div style="display:grid;grid-template-columns:1.15fr 0.85fr;gap:34px;align-items:start;">
            <!-- Left: Composed Legs & Items Summary -->
            <div>
                <div id="request-legs-container" style="display:grid;gap:20px;">
                    <!-- Populated by JS from localStorage or default curated package -->
                </div>
                <div style="margin-top:16px;display:flex;gap:12px;">
                    <a href="/compose" class="btn btn-outline btn-sm">← Modifica o aggiungi esperienze nel Compositore</a>
                    <button type="button" class="btn btn-ghost btn-sm" id="btn-load-curated-demo">
                        Carica Pacchetto Demo Completo (Villa + Auto + Yacht + Jet)
                    </button>
                </div>
            </div>

            <!-- Right: Client Contact Form -->
            <form method="POST" action="/richiesta" id="concierge-request-form" style="background:var(--bg-card);border:1px solid var(--gold-border);border-radius:20px;padding:28px;box-shadow:var(--shadow-luxury);">
                <h2 class="serif" style="font-size:26px;font-weight:600;color:var(--gold-bright);margin-bottom:18px;">Dati Ospite & Desiderata</h2>

                <input type="hidden" name="legs_json" id="form-legs-json" value="">

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                    <div>
                        <label class="form-label" for="req-first-name">Nome *</label>
                        <input type="text" name="first_name" id="req-first-name" class="form-input" required value="<?= e($user ? explode(' ', $user->name)[0] : 'Ginevra') ?>">
                    </div>
                    <div>
                        <label class="form-label" for="req-last-name">Cognome *</label>
                        <input type="text" name="last_name" id="req-last-name" class="form-input" required value="<?= e($user ? (explode(' ', $user->name)[1] ?? 'Borromeo') : 'Borromeo') ?>">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                    <div>
                        <label class="form-label" for="req-email">Email Riservata *</label>
                        <input type="email" name="email" id="req-email" class="form-input" required value="<?= e($user ? $user->email : 'vip@mosaic.digital') ?>">
                    </div>
                    <div>
                        <label class="form-label" for="req-phone">Telefono / WhatsApp VIP *</label>
                        <input type="tel" name="phone" id="req-phone" class="form-input" required value="<?= e($user ? ($user->phone ?? '+41 91 922 8810') : '+41 91 922 8810') ?>">
                    </div>
                </div>

                <div style="margin-bottom:18px;">
                    <label class="form-label" for="req-notes">Preferenze, Orari di Volo, Chef Privato o Security</label>
                    <textarea name="notes" id="req-notes" rows="3" class="form-textarea" placeholder="Indica eventuali allergie, orario preferito per lo slot di volo o consegna auto sottobordo..."></textarea>
                </div>

                <div style="background:rgba(200,155,82,0.09);border:1px solid var(--gold-border);border-radius:14px;padding:16px;margin-bottom:20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:13px;color:var(--text-sand);margin-bottom:6px;">
                        <span>Servizio Private Concierge 24/7</span>
                        <span style="color:#6ee7b7;">Incluso</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:19px;font-weight:700;color:var(--gold-bright);">
                        <span>Totale Stimato Dossier</span>
                        <span id="request-grand-total">€0</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;padding:14px;font-size:15px;">
                    ✦ Invia Richiesta Ufficiale al Concierge
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php if (empty($savedBooking)): ?>
<script>
    const REQ_CATALOG = <?= json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const REQ_DESTINATIONS = <?= json_encode($destinations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

    function loadDefaultCuratedDossier() {
        return {
            activeLegIndex: 0,
            activeCategory: 'villa',
            legs: [
                {
                    destination: 'costa-smeralda',
                    days: 5,
                    adults: 6,
                    children: 0,
                    startDate: '15 Lug 2026',
                    endDate: '20 Lug 2026',
                    selected: {
                        villa: 'vl-01',
                        auto: 'au-08',
                        yacht: 'yc-01',
                        jet: 'jt-01'
                    }
                }
            ]
        };
    }

    let reqState = null;
    try {
        reqState = JSON.parse(localStorage.getItem('mosaic_dossier_v2') || 'null');
    } catch (e) {}

    // Ensure at least one available catalog item is selected so the user never sees an empty checkout
    let hasAnyItem = false;
    if (reqState && Array.isArray(reqState.legs)) {
        reqState.legs.forEach((l) => {
            if (l.selected && typeof l.selected === 'object') {
                ['villa', 'auto', 'yacht', 'jet'].forEach((c) => {
                    const code = l.selected[c];
                    if (code && REQ_CATALOG.some(p => p.code === code)) {
                        hasAnyItem = true;
                    } else if (code) {
                        l.selected[c] = null;
                    }
                });
            }
        });
    }
    if (!hasAnyItem) {
        reqState = loadDefaultCuratedDossier();
        localStorage.setItem('mosaic_dossier_v2', JSON.stringify(reqState));
    }

    function renderRequestSummary() {
        const container = document.getElementById('request-legs-container');
        container.innerHTML = '';
        let grandTotal = 0;
        const payloadLegs = [];

        reqState.legs.forEach((leg, idx) => {
            const destObj = REQ_DESTINATIONS.find(d => d.slug === leg.destination);
            const destName = destObj ? destObj.name : leg.destination;
            const legItems = [];
            let legTotal = 0;

            const card = document.createElement('div');
            card.style.background = 'var(--bg-card)';
            card.style.border = '1px solid var(--border-subtle)';
            card.style.borderRadius = '18px';
            card.style.padding = '22px';

            let itemsHtml = '';
            ['villa', 'auto', 'yacht', 'jet'].forEach((cat) => {
                const code = leg.selected ? leg.selected[cat] : null;
                if (!code) return;
                const prod = REQ_CATALOG.find(p => p.code === code);
                if (!prod) return;
                const qty = (cat === 'jet') ? 1 : (leg.days || 4);
                const sub = prod.price * qty;
                legTotal += sub;
                grandTotal += sub;
                legItems.push({ code: prod.code, product_id: prod.id, category: cat, days: qty });

                itemsHtml += `
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid var(--border-subtle);">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="${(prod.images && prod.images[0]) || '/images/hero.jpg'}" alt="${prod.title}" style="width:68px;height:48px;object-fit:cover;border-radius:8px;">
                            <div>
                                <span class="badge badge-gold" style="font-size:10px;padding:2px 8px;">${cat}</span>
                                <a href="/prodotto/${prod.slug}" class="serif" style="font-size:19px;font-weight:600;margin-left:6px;">${prod.title}</a>
                                <div style="font-size:12px;color:var(--text-sand);">${prod.location} · €${Number(prod.price).toLocaleString('it-IT')} × ${qty} ${prod.unit}</div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-weight:700;font-size:15px;">€${Number(sub).toLocaleString('it-IT')}</div>
                        </div>
                    </div>
                `;
            });

            card.innerHTML = `
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <div>
                        <span class="badge badge-gold">Tappa ${idx + 1}</span>
                        <span class="serif" style="font-size:24px;font-weight:600;margin-left:8px;">${destName}</span>
                    </div>
                    <span style="font-size:13px;color:var(--text-sand);">${leg.days} notti · ${leg.adults} ospiti</span>
                </div>
                <div>${itemsHtml || '<p style="color:var(--text-muted);font-size:13px;">Nessun servizio selezionato in questa tappa.</p>'}</div>
                <div style="display:flex;justify-content:space-between;padding-top:14px;font-weight:600;color:var(--gold-bright);">
                    <span>Subtotale Tappa ${idx + 1}</span>
                    <span>€${Number(legTotal).toLocaleString('it-IT')}</span>
                </div>
            `;
            container.appendChild(card);

            payloadLegs.push({
                destination_slug: leg.destination,
                destination_name: destName,
                start_date: leg.startDate || '15 Lug 2026',
                end_date: leg.endDate || '20 Lug 2026',
                days: leg.days || 4,
                adults: leg.adults || 2,
                children: leg.children || 0,
                categories: legItems.map(i => i.category),
                items: legItems
            });
        });

        document.getElementById('request-grand-total').textContent = '€' + grandTotal.toLocaleString('it-IT');
        document.getElementById('form-legs-json').value = JSON.stringify(payloadLegs);
    }

    document.getElementById('btn-load-curated-demo').addEventListener('click', () => {
        reqState = loadDefaultCuratedDossier();
        localStorage.setItem('mosaic_dossier_v2', JSON.stringify(reqState));
        if (typeof updateDossierCounter === 'function') updateDossierCounter();
        renderRequestSummary();
    });

    renderRequestSummary();
</script>
<?php endif; ?>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
