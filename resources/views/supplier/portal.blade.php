<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding-top:40px;padding-bottom:80px;">
    <?php if ($portalMode === 'standby' && !$previewBypass): ?>
        <!-- LUXURY "IN STANDBY" GATE FOR PUBLIC VISITORS -->
        <div style="max-width:820px;margin:20px auto;background:linear-gradient(145deg, #131b29 0%, #0b0f17 100%);border:1px solid var(--gold);border-radius:28px;padding:48px 40px;text-align:center;box-shadow:var(--shadow-luxury);">
            <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 16px;border-radius:99px;background:rgba(251,191,36,0.14);border:1px solid rgba(251,191,36,0.4);color:#fcd34d;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:20px;">
                <span class="status-dot standby"></span>
                <span>Area Portale Fornitore · In Standby (Accesso Beta Riservato)</span>
            </div>

            <h1 class="serif" style="font-size:clamp(34px, 4.5vw, 50px);font-weight:600;line-height:1.1;margin-bottom:14px;">
                Partner Hub & Accreditamento Fornitori d'Élite
            </h1>
            <p style="font-size:15.5px;color:var(--text-sand);max-width:640px;margin:0 auto 30px;line-height:1.7;">
                L'area self-service per la sincronizzazione diretta di flotte yacht, supercar, dimore storiche e slot di aviazione privata si trova attualmente in modalità <strong>Standby Controllato</strong> per la selezione dei Partner Ufficiali 2026.
            </p>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(210px, 1fr));gap:16px;text-align:left;margin-bottom:34px;">
                <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;">
                    <div style="color:var(--gold-bright);font-weight:700;font-size:13px;margin-bottom:4px;">1. Verifica Standard Gold</div>
                    <p style="font-size:12.5px;color:var(--text-muted);">Audit assicurativo, ispezione flotta/dimora e SLA di risposta entro 30 minuti.</p>
                </div>
                <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;">
                    <div style="color:var(--gold-bright);font-weight:700;font-size:13px;margin-bottom:4px;">2. Calendario & Slot Real-Time</div>
                    <p style="font-size:12.5px;color:var(--text-muted);">Gestione diretta delle opzioni inviate dal Private Concierge Desk.</p>
                </div>
                <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:14px;padding:18px;">
                    <div style="color:var(--gold-bright);font-weight:700;font-size:13px;margin-bottom:4px;">3. Liquidazione Garantita</div>
                    <p style="font-size:12.5px;color:var(--text-muted);">Escrow bancario dedicato e rendicontazione trasparente delle commissioni.</p>
                </div>
            </div>

            <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
                <a href="/portale-fornitore?preview=1" class="btn btn-gold">
                    ✦ Entra in Anteprima Operativa Partner →
                </a>
                <form method="POST" action="/login" style="display:inline;">
                    <input type="hidden" name="demo_email" value="fornitore@mosaic.digital">
                    <input type="hidden" name="redirect" value="/portale-fornitore">
                    <button type="submit" class="btn btn-outline">
                        Autenticati come Fornitore/Partner (RBAC)
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- OPERATIONAL PARTNER DASHBOARD -->
        <?php if ($portalMode === 'standby'): ?>
            <div style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.38);border-radius:14px;padding:12px 18px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;font-size:13px;">
                <div>
                    <span class="badge badge-amber" style="margin-right:8px;">Modalità Standby Attiva per il Pubblico</span>
                    <span>Stai visualizzando la <strong>Console Operativa Partner</strong> grazie ai tuoi permessi RBAC / Anteprima.</span>
                </div>
                <a href="/portale-fornitore" class="btn btn-ghost btn-sm">Mostra Schermata Gate Standby</a>
            </div>
        <?php endif; ?>

        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:28px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <span class="badge badge-gold"><?= e($activeSupplier['badge_label'] ?? 'Partner Verificato') ?></span>
                    <span class="badge badge-green">Rating ★ <?= e($activeSupplier['rating'] ?? '4.98') ?></span>
                </div>
                <h1 class="serif" style="font-size:40px;font-weight:600;"><?= e($activeSupplier['company_name'] ?? 'Smeralda Estates') ?></h1>
                <p style="font-size:14px;color:var(--text-sand);">
                    📍 <?= e($activeSupplier['location'] ?? '') ?> · Referente: <strong><?= e($activeSupplier['contact_name'] ?? '') ?></strong> (<?= e($activeSupplier['email'] ?? '') ?>)
                </p>
            </div>

            <form method="GET" action="/portale-fornitore" style="display:flex;align-items:center;gap:10px;">
                <input type="hidden" name="preview" value="1">
                <label for="sup-switch-select" style="font-size:12px;color:var(--text-muted);">Ispeziona Partner:</label>
                <select name="supplier_id" id="sup-switch-select" class="form-select" style="width:auto;" onchange="this.form.submit()">
                    <?php foreach ($allSuppliers as $s): ?>
                        <option value="<?= (int)$s['id'] ?>" <?= ($activeSupplier && (int)$activeSupplier['id'] === (int)$s['id']) ? 'selected' : '' ?>>
                            <?= e($s['company_name']) ?> (<?= (int)$s['products_count'] ?> asset)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Partner Fleet / Dimore Linked via supplier_id -->
        <div style="margin-bottom:38px;">
            <h2 class="serif" style="font-size:28px;color:var(--gold-bright);margin-bottom:14px;">
                Flotta & Dimore Associate (<?= count($supplierProducts) ?>)
            </h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:20px;">
                <?php foreach ($supplierProducts as $sp): ?>
                    <article style="background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;">
                        <img src="<?= e($sp['images'][0] ?? '/images/hero.jpg') ?>" alt="<?= e($sp['title']) ?>" style="width:100%;height:170px;object-fit:cover;">
                        <div style="padding:18px;display:flex;flex-direction:column;flex:1;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                                <span class="badge badge-gold"><?= e($sp['category']) ?> · <?= e($sp['code']) ?></span>
                                <span class="badge <?= $sp['is_available'] ? 'badge-green' : 'badge-red' ?>">
                                    <?= $sp['is_available'] ? 'Disponibile' : 'Sospeso' ?>
                                </span>
                            </div>
                            <h3 class="serif" style="font-size:22px;font-weight:600;margin-bottom:4px;"><?= e($sp['title']) ?></h3>
                            <p style="font-size:12.5px;color:var(--text-sand);margin-bottom:12px;"><?= e($sp['subtitle']) ?></p>
                            <div style="margin-top:auto;padding-top:12px;border-top:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;">
                                <strong><?= format_eur($sp['price']) ?> / <?= e($sp['unit']) ?></strong>
                                <a href="/prodotto/<?= e($sp['slug']) ?>" class="btn btn-outline btn-sm">Scheda Pubblica →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Incoming Booking Items for this Supplier -->
        <div class="table-wrapper" tabindex="0" role="region" aria-labelledby="supplier-bookings-caption">
            <table class="luxury-table">
                <caption id="supplier-bookings-caption">Richieste di Prenotazione & Opzioni Ricevute dal Private Concierge</caption>
                <thead>
                    <tr>
                        <th scope="col">Dossier</th>
                        <th scope="col">Ospite</th>
                        <th scope="col">Asset Richiesto</th>
                        <th scope="col">Date & Durata</th>
                        <th scope="col" class="numeric">Valore Servizio</th>
                        <th scope="col">Stato Partner</th>
                        <th scope="col">Conferma Disponibilità</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($supplierBookingItems)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Nessuna richiesta pendente per questo partner.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($supplierBookingItems as $bi): ?>
                            <tr>
                                <td data-label="Dossier"><strong style="color:var(--gold-bright);"><?= e($bi['reference_code']) ?></strong></td>
                                <td data-label="Ospite"><?= e($bi['client_first_name'] . ' ' . $bi['client_last_name']) ?></td>
                                <td data-label="Asset Richiesto"><strong><?= e($bi['product_title']) ?></strong></td>
                                <td data-label="Date & Durata">
                                    <?= e($bi['start_date'] ?? '15 Lug') ?> – <?= e($bi['end_date'] ?? '20 Lug') ?>
                                    (<?= (int)$bi['quantity_days'] ?> <?= e($bi['unit_label']) ?>)
                                </td>
                                <td data-label="Valore Servizio" class="numeric"><strong><?= format_eur($bi['subtotal']) ?></strong></td>
                                <td data-label="Stato Partner">
                                    <span class="badge <?= $bi['supplier_status'] === 'confermato' ? 'badge-green' : 'badge-amber' ?>">
                                        <?= e($bi['supplier_status']) ?>
                                    </span>
                                </td>
                                <td data-label="Conferma Disponibilità">
                                    <form method="POST" action="/portale-fornitore/items/<?= (int)$bi['id'] ?>/status" style="display:flex;gap:6px;">
                                        <input type="hidden" name="supplier_status" value="confermato">
                                        <button type="submit" class="btn btn-gold btn-sm">Conferma Slot ✓</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
