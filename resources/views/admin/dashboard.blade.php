<?php
require resource_path('views/layouts/header.blade.php');

$statusBadges = [
    'nuova' => ['label' => 'Nuova Richiesta', 'class' => 'badge-blue'],
    'offerta_inviata' => ['label' => 'Offerta Inviata', 'class' => 'badge-gold'],
    'confermata' => ['label' => 'Confermata', 'class' => 'badge-green'],
    'pagata' => ['label' => 'Saldata / Pagata', 'class' => 'badge-green'],
    'annullata' => ['label' => 'Annullata', 'class' => 'badge-red'],
];
?>

<style>
    .admin-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 32px;
    }
    .admin-kpi-card {
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 20px 22px;
    }
    .admin-nav-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid var(--border-subtle);
        padding-bottom: 14px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }
    .admin-nav-tab {
        padding: 10px 20px;
        border-radius: var(--radius-pill);
        border: 1px solid var(--border-subtle);
        background: rgba(255, 255, 255, 0.03);
        color: var(--text-sand);
        font-size: 13.5px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .admin-nav-tab.active {
        border-color: var(--gold);
        background: var(--gold-muted);
        color: var(--gold-bright);
    }
    .admin-panel { display: none; }
    .admin-panel.active { display: block; }
</style>

<div class="container" style="padding-top:34px;padding-bottom:80px;">
    <!-- Admin Header -->
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:28px;">
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="badge badge-gold">Console Governance & Preventivi</span>
                <span class="badge badge-green">Ruolo: <?= e($user->primaryRole()) ?></span>
            </div>
            <h1 class="serif" style="font-size:40px;font-weight:600;">Pannello di Controllo MOSAIC</h1>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <?php if ($user->hasAnyPermission(['catalog.create', 'catalog.edit'])): ?>
                <button type="button" class="btn btn-gold btn-sm" onclick="openProductModal()">
                    + Nuova Offerta a Catalogo
                </button>
            <?php endif; ?>
            <a href="/portale-fornitore?preview=1" class="btn btn-outline btn-sm">Ispeziona Portale Fornitore →</a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="admin-kpi-grid">
        <div class="admin-kpi-card">
            <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.06em;">Pipeline Dossier Attivi</div>
            <div class="serif" style="font-size:34px;font-weight:700;color:var(--gold-bright);margin:4px 0;"><?= format_eur($kpis['pipeline_eur']) ?></div>
            <div style="font-size:12px;color:var(--text-sand);"><?= $kpis['bookings_total'] ?> richieste totali a sistema</div>
        </div>
        <div class="admin-kpi-card">
            <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.06em;">Confermato & Saldato</div>
            <div class="serif" style="font-size:34px;font-weight:700;color:#6ee7b7;margin:4px 0;"><?= format_eur($kpis['confirmed_eur']) ?></div>
            <div style="font-size:12px;color:var(--text-sand);"><?= $kpis['bookings_confirmed'] ?> dossier confermati</div>
        </div>
        <div class="admin-kpi-card">
            <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.06em;">Catalogo Esperienze</div>
            <div class="serif" style="font-size:34px;font-weight:700;color:var(--text-ivory);margin:4px 0;"><?= $kpis['products_total'] ?></div>
            <div style="font-size:12px;color:var(--text-sand);">Ville, Auto, Yacht, Jet & Elicotteri</div>
        </div>
        <div class="admin-kpi-card">
            <div style="font-size:11px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.06em;">Partner & Portale Fornitori</div>
            <div class="serif" style="font-size:34px;font-weight:700;color:var(--text-ivory);margin:4px 0;"><?= $kpis['suppliers_total'] ?></div>
            <div style="font-size:12px;color:var(--gold-bright);">
                Stato Portale: <strong><?= $supplierPortalMode === 'active' ? 'OPERATIVO (LIVE)' : 'IN STANDBY (BETA)' ?></strong>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="admin-nav-tabs" role="tablist">
        <button type="button" class="admin-nav-tab <?= $activeTab === 'bookings' ? 'active' : '' ?>" onclick="switchAdminTab('bookings', this)">
            1. Prenotazioni & Preventivi Su Misura (<?= count($bookings) ?>)
        </button>
        <button type="button" class="admin-nav-tab <?= $activeTab === 'catalog' ? 'active' : '' ?>" onclick="switchAdminTab('catalog', this)">
            2. Offerte Suddivise per Tipologia (<?= count($products) ?>)
        </button>
        <button type="button" class="admin-nav-tab <?= $activeTab === 'suppliers' ? 'active' : '' ?>" onclick="switchAdminTab('suppliers', this)">
            3. Fornitori & Switch Portale Standby (<?= count($suppliers) ?>)
        </button>
        <button type="button" class="admin-nav-tab <?= $activeTab === 'rbac' ? 'active' : '' ?>" onclick="switchAdminTab('rbac', this)">
            4. Ruoli & Permessi Spatie RBAC (<?= count($roles) ?>)
        </button>
    </div>

    <!-- TAB 1: BOOKINGS & CUSTOM QUOTES -->
    <section class="admin-panel <?= $activeTab === 'bookings' ? 'active' : '' ?>" id="admin-tab-bookings">
        <div class="table-wrapper" tabindex="0" role="region" aria-labelledby="bookings-table-caption">
            <table class="luxury-table">
                <caption id="bookings-table-caption">Registro Richieste Clienti & Preventivi Personalizzati del Concierge</caption>
                <thead>
                    <tr>
                        <th scope="col">Riferimento</th>
                        <th scope="col">Ospite VIP</th>
                        <th scope="col">Itinerario & Categorie</th>
                        <th scope="col">Stato Workflow</th>
                        <th scope="col" class="numeric">Listino Stimato</th>
                        <th scope="col" class="numeric">Offerta Concierge</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b):
                        $st = $statusBadges[$b['status']] ?? ['label' => $b['status'], 'class' => 'badge-gold'];
                    ?>
                        <tr>
                            <td data-label="Riferimento">
                                <strong style="color:var(--gold-bright);"><?= e($b['reference_code']) ?></strong>
                                <div style="font-size:11px;color:var(--text-muted);"><?= e(substr($b['created_at'], 0, 10)) ?></div>
                            </td>
                            <td data-label="Ospite VIP">
                                <strong><?= e($b['client_first_name'] . ' ' . $b['client_last_name']) ?></strong>
                                <div style="font-size:11.5px;color:var(--text-sand);"><?= e($b['client_email']) ?></div>
                            </td>
                            <td data-label="Itinerario & Categorie">
                                <?php foreach (($b['legs'] ?? []) as $leg): ?>
                                    <div style="font-size:12px;margin-bottom:4px;">
                                        <span class="badge badge-gold" style="font-size:10px;padding:1px 7px;">Tappa <?= (int)$leg['leg_index'] ?></span>
                                        <strong><?= e($leg['destination_name']) ?></strong> (<?= (int)$leg['days'] ?>g · <?= (int)$leg['adults'] ?> ospiti)
                                    </div>
                                <?php endforeach; ?>
                                <div style="font-size:11.5px;color:var(--text-muted);">
                                    <?= count($b['items'] ?? []) ?> servizi: <?= e(implode(', ', array_column($b['items'] ?? [], 'product_title'))) ?>
                                </div>
                            </td>
                            <td data-label="Stato Workflow">
                                <span class="badge <?= $st['class'] ?>"><?= e($st['label']) ?></span>
                            </td>
                            <td data-label="Listino Stimato" class="numeric">
                                <?= format_eur($b['total_estimated']) ?>
                            </td>
                            <td data-label="Offerta Concierge" class="numeric">
                                <?php if (!empty($b['custom_total_offer'])): ?>
                                    <strong style="color:#6ee7b7;"><?= format_eur($b['custom_total_offer']) ?></strong>
                                    <?php if ((int)$b['discount_percent'] > 0): ?>
                                        <div style="font-size:11px;color:var(--gold-bright);">Sconto VIP -<?= (int)$b['discount_percent'] ?>%</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);">Da quotare</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Azioni">
                                <button
                                    type="button"
                                    class="btn btn-outline btn-sm"
                                    onclick='openBookingOfferModal(<?= json_encode($b, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>)'
                                >
                                    Formula Offerta / Stato
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- TAB 2: OFFERS SUBDIVIDED BY TYPOLOGY (VILLA, AUTO, YACHT, JET) -->
    <section class="admin-panel <?= $activeTab === 'catalog' ? 'active' : '' ?>" id="admin-tab-catalog">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
            <div style="display:flex;gap:8px;flex-wrap:wrap;" id="admin-cat-subtabs">
                <button type="button" class="filter-pill active" onclick="filterAdminCatalog('all', this)">Tutte (<?= count($products) ?>)</button>
                <button type="button" class="filter-pill" onclick="filterAdminCatalog('villa', this)">🏛️ Ville (<?= count($productsByCat['villa']) ?>)</button>
                <button type="button" class="filter-pill" onclick="filterAdminCatalog('auto', this)">🏎️ Auto (<?= count($productsByCat['auto']) ?>)</button>
                <button type="button" class="filter-pill" onclick="filterAdminCatalog('yacht', this)">🛥️ Yacht (<?= count($productsByCat['yacht']) ?>)</button>
                <button type="button" class="filter-pill" onclick="filterAdminCatalog('jet', this)">✈️ Jet & Elicotteri (<?= count($productsByCat['jet']) ?>)</button>
            </div>
            <button type="button" class="btn btn-gold btn-sm" onclick="openProductModal()">+ Aggiungi Offerta</button>
        </div>

        <div class="table-wrapper" tabindex="0" role="region" aria-labelledby="catalog-table-caption">
            <table class="luxury-table">
                <caption id="catalog-table-caption">Catalogo Esperienze Suddiviso per Tipologia</caption>
                <thead>
                    <tr>
                        <th scope="col">Codice</th>
                        <th scope="col">Tipologia</th>
                        <th scope="col">Offerta & Sottotitolo</th>
                        <th scope="col">Destinazione & Località</th>
                        <th scope="col">Fornitore Associato</th>
                        <th scope="col" class="numeric">Tariffa Base</th>
                        <th scope="col">Disponibilità</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr class="admin-catalog-row" data-cat="<?= e($p['category']) ?>">
                            <td data-label="Codice"><code style="color:var(--gold-bright);"><?= e($p['code']) ?></code></td>
                            <td data-label="Tipologia">
                                <span class="badge badge-gold"><?= e($p['category']) ?></span>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;"><?= e($p['subtype'] ?? '') ?></div>
                            </td>
                            <td data-label="Offerta">
                                <a href="/prodotto/<?= e($p['slug']) ?>" style="font-weight:700;color:var(--text-ivory);"><?= e($p['title']) ?></a>
                                <div style="font-size:11.5px;color:var(--text-sand);"><?= e($p['subtitle']) ?></div>
                            </td>
                            <td data-label="Località"><?= e($p['location']) ?></td>
                            <td data-label="Fornitore"><?= e($p['supplier_company'] ?? 'Mosaic Partner') ?></td>
                            <td data-label="Tariffa Base" class="numeric">
                                <strong><?= format_eur($p['price']) ?></strong> / <?= e($p['unit']) ?>
                            </td>
                            <td data-label="Disponibilità">
                                <form method="POST" action="/admin/products/<?= (int)$p['id'] ?>/toggle" style="display:inline;">
                                    <button type="submit" class="badge <?= $p['is_available'] ? 'badge-green' : 'badge-red' ?>" style="cursor:pointer;">
                                        <?= $p['is_available'] ? '✓ Attivo' : '✕ Sospeso' ?>
                                    </button>
                                </form>
                            </td>
                            <td data-label="Azioni">
                                <div style="display:flex;gap:6px;">
                                    <button
                                        type="button"
                                        class="btn btn-ghost btn-sm"
                                        onclick='openProductModal(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>)'
                                    >
                                        Modifica
                                    </button>
                                    <a href="/prodotto/<?= e($p['slug']) ?>" class="btn btn-outline btn-sm">Scheda</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- TAB 3: SUPPLIERS & STANDBY MASTER SWITCH -->
    <section class="admin-panel <?= $activeTab === 'suppliers' ? 'active' : '' ?>" id="admin-tab-suppliers">
        <div style="background:linear-gradient(135deg, rgba(200,155,82,0.14) 0%, rgba(17,23,35,0.95) 100%);border:1px solid var(--gold);border-radius:20px;padding:26px;margin-bottom:28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <span class="badge <?= $supplierPortalMode === 'active' ? 'badge-green' : 'badge-amber' ?>">
                        Stato Attuale: <?= $supplierPortalMode === 'active' ? 'OPERATIVO (LIVE)' : 'IN STANDBY (GATE ATTIVO)' ?>
                    </span>
                    <span style="font-size:12px;color:var(--text-sand);">Permesso richiesto: <code>suppliers.toggle_standby</code></span>
                </div>
                <h2 class="serif" style="font-size:28px;font-weight:600;">Master Switch Area Portale Fornitore</h2>
                <p style="font-size:13.5px;color:var(--text-sand);max-width:680px;">
                    In modalità <strong>Standby</strong>, i visitatori non autenticati vedono la pagina di accreditamento riservato ("Area Portale Fornitore in Standby"), mentre Partner e Admin possono comunque ispezionare la dashboard operativa.
                </p>
            </div>
            <?php if ($user->hasAnyPermission(['suppliers.toggle_standby', 'settings.manage'])): ?>
                <form method="POST" action="/admin/suppliers/toggle-portal">
                    <input type="hidden" name="mode" value="<?= $supplierPortalMode === 'standby' ? 'active' : 'standby' ?>">
                    <button type="submit" class="btn btn-gold">
                        <?= $supplierPortalMode === 'standby' ? 'Attiva Portale Fornitore (Live) →' : 'Imposta Portale in Standby ⏸' ?>
                    </button>
                </form>
            <?php else: ?>
                <span class="badge badge-red">Solo Super Admin può commutare lo stato globale</span>
            <?php endif; ?>
        </div>

        <div class="table-wrapper" tabindex="0" role="region" aria-labelledby="suppliers-table-caption">
            <table class="luxury-table">
                <caption id="suppliers-table-caption">Anagrafica Fornitori Accreditati & Partner Hub (15 Operatori)</caption>
                <thead>
                    <tr>
                        <th scope="col">Partner / Ragione Sociale</th>
                        <th scope="col">Referente & Contatti</th>
                        <th scope="col">Base Operativa</th>
                        <th scope="col">Categorie</th>
                        <th scope="col" class="numeric">Prodotti</th>
                        <th scope="col" class="numeric">Commissione</th>
                        <th scope="col">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $sup): ?>
                        <tr>
                            <td data-label="Partner">
                                <strong><?= e($sup['company_name']) ?></strong>
                                <div style="font-size:11px;color:var(--gold-bright);">★ <?= e($sup['rating']) ?> · <?= e($sup['badge_label']) ?></div>
                            </td>
                            <td data-label="Referente">
                                <?= e($sup['contact_name']) ?>
                                <div style="font-size:11.5px;color:var(--text-muted);"><?= e($sup['email']) ?></div>
                            </td>
                            <td data-label="Base Operativa"><?= e($sup['location']) ?></td>
                            <td data-label="Categorie">
                                <?php foreach ($sup['categories'] as $sc): ?>
                                    <span class="badge badge-gold"><?= e($sc) ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td data-label="Prodotti" class="numeric"><strong><?= (int)$sup['products_count'] ?></strong></td>
                            <td data-label="Commissione" class="numeric"><?= number_format((float)$sup['commission_rate'], 1) ?>%</td>
                            <td data-label="Azioni">
                                <a href="/portale-fornitore?supplier_id=<?= (int)$sup['id'] ?>&preview=1" class="btn btn-outline btn-sm">
                                    Apri Vista Partner →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- TAB 4: SPATIE RBAC ROLES & PERMISSIONS MATRIX -->
    <section class="admin-panel <?= $activeTab === 'rbac' ? 'active' : '' ?>" id="admin-tab-rbac">
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(290px, 1fr));gap:20px;margin-bottom:28px;">
            <?php
            $allPermissionsFlat = [];
            foreach ($permissionsGrouped as $grp => $pList) {
                foreach ($pList as $pObj) {
                    $allPermissionsFlat[] = $pObj;
                }
            }
            foreach ($roles as $r):
                $rPermNames = array_column($r['permissions'] ?? [], 'name');
                $rPermIds = array_map('intval', array_column($r['permissions'] ?? [], 'id'));
            ?>
                <div style="background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:18px;padding:22px;display:flex;flex-direction:column;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span class="badge badge-gold"><?= e($r['name']) ?></span>
                        <span style="font-size:12px;color:var(--text-muted);">Guard: <code><?= e($r['guard_name']) ?></code></span>
                    </div>
                    <p style="font-size:13px;color:var(--text-sand);margin-bottom:14px;"><?= e($r['description']) ?></p>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;">
                        <?php foreach ($rPermNames as $pn): ?>
                            <code style="font-size:11px;padding:3px 8px;border-radius:6px;background:rgba(255,255,255,0.05);color:var(--gold-bright);">
                                <?= e($pn) ?>
                            </code>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($user->can('roles.manage')): ?>
                        <details style="margin-top:auto;padding-top:12px;border-top:1px solid var(--border-subtle);">
                            <summary style="cursor:pointer;font-size:12px;font-weight:600;color:var(--gold-bright);">
                                ⚙ Modifica Permessi Ruolo (<?= count($rPermIds) ?>)
                            </summary>
                            <form method="POST" action="/admin/roles/<?= (int)$r['id'] ?>/permissions" style="margin-top:10px;display:grid;gap:6px;">
                                <div style="max-height:170px;overflow-y:auto;display:grid;gap:5px;padding-right:4px;">
                                    <?php foreach ($allPermissionsFlat as $perm): ?>
                                        <label style="display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--text-sand);cursor:pointer;">
                                            <input
                                                type="checkbox"
                                                name="permission_ids[]"
                                                value="<?= (int)$perm['id'] ?>"
                                                <?= in_array((int)$perm['id'], $rPermIds, true) ? 'checked' : '' ?>
                                                style="accent-color:var(--gold);"
                                            >
                                            <span><code><?= e($perm['name']) ?></code></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <button type="submit" class="btn btn-outline btn-sm" style="margin-top:6px;width:100%;">
                                    Salva Matrice <?= e($r['name']) ?>
                                </button>
                            </form>
                        </details>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="table-wrapper" tabindex="0" role="region" aria-labelledby="users-rbac-caption">
            <table class="luxury-table">
                <caption id="users-rbac-caption">Utenti Accreditati & Assegnazione Ruoli Spatie (`model_has_roles`)</caption>
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome Completo</th>
                        <th scope="col">Email</th>
                        <th scope="col">Organizzazione / Tier</th>
                        <th scope="col">Ruolo Spatie Assegnato</th>
                        <th scope="col">Permessi Effettivi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $uItem): ?>
                        <tr>
                            <td data-label="ID">#<?= $uItem->id ?></td>
                            <td data-label="Nome"><strong><?= e($uItem->name) ?></strong></td>
                            <td data-label="Email"><code><?= e($uItem->email) ?></code></td>
                            <td data-label="Organizzazione"><?= e($uItem->company_name) ?> · <span class="badge badge-blue"><?= e($uItem->vip_tier) ?></span></td>
                            <td data-label="Ruolo Spatie">
                                <?php foreach ($uItem->getRoleNames() as $rn): ?>
                                    <span class="badge badge-gold"><?= e($rn) ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td data-label="Permessi Effettivi">
                                <?= count($uItem->getPermissionNames()) ?> permessi attivi
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- MODAL 1: CUSTOM BOOKING OFFER & WORKFLOW (<dialog closedby="any">) -->
<dialog id="modal-booking-offer" class="luxury-dialog" closedby="any" aria-labelledby="offer-modal-title">
    <div class="dialog-header">
        <div>
            <div class="serif" style="font-size:24px;font-weight:600;color:var(--gold-bright);" id="offer-modal-title">Gestione Preventivo Su Misura</div>
            <div style="font-size:12px;color:var(--text-sand);" id="offer-modal-sub">Formula un'offerta personalizzata per l'Ospite VIP</div>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('modal-booking-offer').close()">✕</button>
    </div>
    <div class="dialog-body">
        <form method="POST" action="" id="form-booking-offer" data-base-total="0">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="offer-status">Stato Workflow Richiesta</label>
                    <select name="status" id="offer-status" class="form-select">
                        <option value="nuova">Nuova Richiesta</option>
                        <option value="offerta_inviata">Offerta Inviata al Cliente</option>
                        <option value="confermata">Confermata dal Cliente</option>
                        <option value="pagata">Saldata / Pagata</option>
                        <option value="annullata">Annullata</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="offer-discount">Sconto Riservato VIP (%)</label>
                    <input type="number" name="discount_percent" id="offer-discount" class="form-input" min="0" max="50" value="0">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="offer-custom-total">Importo Offerta Su Misura (€)</label>
                    <input type="number" name="custom_total_offer" id="offer-custom-total" class="form-input" placeholder="Calcolato automaticamente o manuale">
                </div>
                <div>
                    <label class="form-label" for="offer-valid-until">Validità Offerta</label>
                    <input type="text" name="offer_valid_until" id="offer-valid-until" class="form-input" value="30 Giu 2026">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label" for="offer-notes">Note Riservate del Private Concierge (visibili nell'Area Clienti VIP)</label>
                <textarea name="concierge_notes" id="offer-notes" rows="3" class="form-textarea"></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-booking-offer').close()">Annulla</button>
                <button type="submit" class="btn btn-gold">Salva & Invia Offerta al Cliente →</button>
            </div>
        </form>
    </div>
</dialog>

<!-- MODAL 2: CREATE / EDIT CATALOG PRODUCT (<dialog closedby="any">) -->
<dialog id="modal-product-crud" class="luxury-dialog" closedby="any" aria-labelledby="prod-modal-heading">
    <div class="dialog-header">
        <div>
            <div class="serif" style="font-size:24px;font-weight:600;color:var(--gold-bright);" id="prod-modal-heading">Scheda Offerta a Catalogo</div>
            <div style="font-size:12px;color:var(--text-sand);">Gestione Ville, Supercar, Yacht e Jet Privati</div>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('modal-product-crud').close()">✕</button>
    </div>
    <div class="dialog-body">
        <form method="POST" action="/admin/products/save" id="form-product-crud">
            <input type="hidden" name="id" id="prod-id" value="0">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="prod-title">Titolo Esperienza / Modello *</label>
                    <input type="text" name="title" id="prod-title" class="form-input" required placeholder="Es. Villa La Contessa">
                </div>
                <div>
                    <label class="form-label" for="prod-category">Tipologia / Categoria *</label>
                    <select name="category" id="prod-category" class="form-select">
                        <option value="villa">🏛️ Villa / Dimora</option>
                        <option value="auto">🏎️ Auto / Supercar</option>
                        <option value="yacht">🛥️ Barca / Yacht</option>
                        <option value="jet">✈️ Jet Privato / Elicottero VIP</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="prod-dest">Destinazione</label>
                    <select name="destination_slug" id="prod-dest" class="form-select">
                        <?php foreach ($destinations as $d): ?>
                            <option value="<?= e($d['slug']) ?>"><?= e($d['slug'] === 'costa-smeralda' ? 'Costa Smeralda' : $d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="prod-price">Prezzo Unitario (€)</label>
                    <input type="number" name="price" id="prod-price" class="form-input" value="4500" required>
                </div>
                <div>
                    <label class="form-label" for="prod-cap">Capienza (Ospiti/Posti)</label>
                    <input type="number" name="capacity" id="prod-cap" class="form-input" value="8" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="prod-location">Località Specifica</label>
                    <input type="text" name="location" id="prod-location" class="form-input" value="Porto Cervo, Costa Smeralda">
                </div>
                <div>
                    <label class="form-label" for="prod-supplier">Fornitore Partner</label>
                    <select name="supplier_id" id="prod-supplier" class="form-select">
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= (int)$s['id'] ?>"><?= e($s['company_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:14px;align-items:end;margin-bottom:14px;">
                <div>
                    <label class="form-label" for="prod-subtype">Sotto-Categoria / Stile</label>
                    <input type="text" name="subtype" id="prod-subtype" class="form-input" placeholder="Es. Villa con Piscina / SUV">
                </div>
                <div>
                    <label class="form-label" for="prod-badge">Badge Editoriale</label>
                    <input type="text" name="badge" id="prod-badge" class="form-input" placeholder="Es. VISTA MARE / ICONA">
                </div>
                <label style="display:flex;align-items:center;gap:8px;padding:11px 14px;border:1px solid var(--border-subtle);border-radius:var(--radius-sm);cursor:pointer;font-size:13px;">
                    <input type="checkbox" name="featured" id="prod-featured" value="1" style="accent-color:var(--gold);">
                    <span>In Primo Piano</span>
                </label>
            </div>

            <div style="margin-bottom:14px;">
                <label class="form-label" for="prod-subtitle">Sottotitolo Sintetico</label>
                <input type="text" name="subtitle" id="prod-subtitle" class="form-input" placeholder="Es. 6 camere · Piscina Infinity · Accesso privato al mare">
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label" for="prod-desc">Descrizione Editoriale</label>
                <textarea name="description" id="prod-desc" rows="3" class="form-textarea"></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-product-crud').close()">Annulla</button>
                <button type="submit" class="btn btn-gold">Salva a Catalogo →</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function switchAdminTab(tabName, btnEl) {
        document.querySelectorAll('.admin-nav-tab').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
        btnEl.classList.add('active');
        const target = document.getElementById('admin-tab-' + tabName);
        if (target) target.classList.add('active');
    }

    function filterAdminCatalog(cat, btnEl) {
        document.querySelectorAll('#admin-cat-subtabs .filter-pill').forEach(b => b.classList.remove('active'));
        btnEl.classList.add('active');
        document.querySelectorAll('.admin-catalog-row').forEach((row) => {
            row.style.display = (cat === 'all' || row.dataset.cat === cat) ? '' : 'none';
        });
    }

    function openBookingOfferModal(booking) {
        const dialog = document.getElementById('modal-booking-offer');
        const form = document.getElementById('form-booking-offer');
        const baseTotal = parseInt(booking.total_estimated, 10) || 0;
        form.action = `/admin/bookings/${booking.id}/offer`;
        form.dataset.baseTotal = String(baseTotal);
        document.getElementById('offer-modal-title').textContent = `Preventivo Dossier ${booking.reference_code}`;
        document.getElementById('offer-modal-sub').textContent = `Ospite: ${booking.client_first_name} ${booking.client_last_name} · Listino: €${Number(baseTotal).toLocaleString('it-IT')}`;
        document.getElementById('offer-status').value = booking.status || 'offerta_inviata';
        document.getElementById('offer-discount').value = booking.discount_percent || 0;
        document.getElementById('offer-custom-total').value = booking.custom_total_offer || baseTotal;
        document.getElementById('offer-valid-until').value = booking.offer_valid_until || '30 Giu 2026';
        document.getElementById('offer-notes').value = booking.concierge_notes || '';
        dialog.showModal();
    }

    document.getElementById('offer-discount').addEventListener('input', (e) => {
        const form = document.getElementById('form-booking-offer');
        const baseTotal = parseInt(form.dataset.baseTotal || '0', 10);
        const disc = Math.max(0, Math.min(50, parseInt(e.target.value || '0', 10)));
        if (baseTotal > 0) {
            document.getElementById('offer-custom-total').value = String(Math.round(baseTotal * (100 - disc) / 100));
        }
    });

    function openProductModal(product = null) {
        const dialog = document.getElementById('modal-product-crud');
        document.getElementById('prod-id').value = product ? product.id : 0;
        document.getElementById('prod-modal-heading').textContent = product ? `Modifica: ${product.title}` : 'Nuova Offerta a Catalogo';
        document.getElementById('prod-title').value = product ? product.title : '';
        document.getElementById('prod-category').value = product ? product.category : 'villa';
        document.getElementById('prod-dest').value = product ? product.destination_slug : 'costa-smeralda';
        document.getElementById('prod-price').value = product ? product.price : 4500;
        document.getElementById('prod-cap').value = product ? product.capacity : 6;
        document.getElementById('prod-location').value = product ? product.location : 'Porto Cervo, Costa Smeralda';
        document.getElementById('prod-supplier').value = product && product.supplier_id ? product.supplier_id : 1;
        document.getElementById('prod-subtype').value = product && product.subtype ? product.subtype : 'Esclusivo';
        document.getElementById('prod-badge').value = product && product.badge ? product.badge : '';
        document.getElementById('prod-featured').checked = Boolean(product && product.featured);
        document.getElementById('prod-subtitle').value = product ? product.subtitle : '';
        document.getElementById('prod-desc').value = product ? product.description : '';
        dialog.showModal();
    }
</script>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
