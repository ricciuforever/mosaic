<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding-top:38px;padding-bottom:80px;">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:16px;margin-bottom:32px;">
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                <span class="badge badge-gold">Area Riservata Cliente VIP</span>
                <span class="badge badge-green"><?= e($user->vip_tier ?? 'Black Member') ?></span>
            </div>
            <h1 class="serif" style="font-size:40px;font-weight:600;">Bentornata, <?= e($user->name) ?></h1>
            <p style="font-size:14px;color:var(--text-sand);">
                Consulta lo stato dei tuoi dossier multi-categoria e approva in 1 click le proposte formulate dal Private Concierge Desk.
            </p>
        </div>
        <a href="/compose" class="btn btn-gold">+ Componi Nuovo Soggiorno</a>
    </div>

    <div style="display:grid;gap:24px;">
        <?php foreach ($bookings as $b): ?>
            <article style="background:var(--bg-card);border:1px solid var(--gold-border);border-radius:20px;padding:28px;box-shadow:var(--shadow-luxury);">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;padding-bottom:16px;border-bottom:1px solid var(--border-subtle);margin-bottom:18px;">
                    <div>
                        <span class="badge badge-gold" style="margin-right:8px;"><?= e($b['reference_code']) ?></span>
                        <strong class="serif" style="font-size:24px;">
                            Dossier <?= e(implode(' + ', array_column($b['legs'] ?? [], 'destination_name'))) ?>
                        </strong>
                    </div>
                    <span class="badge <?= in_array($b['status'], ['confermata', 'pagata'], true) ? 'badge-green' : 'badge-amber' ?>">
                        Stato: <?= e(strtoupper(str_replace('_', ' ', $b['status']))) ?>
                    </span>
                </div>

                <div style="display:grid;grid-template-columns:1.3fr 0.7fr;gap:26px;align-items:start;">
                    <div>
                        <h3 style="font-size:12px;text-transform:uppercase;color:var(--text-muted);letter-spacing:0.08em;margin-bottom:12px;">
                            Servizi Combinati nel Dossier
                        </h3>
                        <div style="display:grid;gap:10px;">
                            <?php foreach (($b['items'] ?? []) as $it): ?>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;background:rgba(255,255,255,0.03);border:1px solid var(--border-subtle);border-radius:12px;">
                                    <div>
                                        <span class="badge badge-gold" style="font-size:10px;margin-right:6px;"><?= e($it['category']) ?></span>
                                        <strong><?= e($it['product_title']) ?></strong>
                                        <span style="font-size:12px;color:var(--text-sand);"> · <?= (int)$it['quantity_days'] ?> <?= e($it['unit_label']) ?></span>
                                    </div>
                                    <strong><?= format_eur($it['subtotal']) ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="background:rgba(200,155,82,0.08);border:1px solid var(--gold-border);border-radius:16px;padding:20px;">
                        <div style="font-size:11px;text-transform:uppercase;color:var(--gold-bright);letter-spacing:0.08em;margin-bottom:6px;">
                            Proposta Riservata Private Concierge
                        </div>
                        <?php if (!empty($b['custom_total_offer'])): ?>
                            <div style="font-size:13px;color:var(--text-muted);text-decoration:line-through;">
                                Listino: <?= format_eur($b['total_estimated']) ?>
                            </div>
                            <div class="serif" style="font-size:32px;font-weight:700;color:#6ee7b7;margin:2px 0 8px;">
                                <?= format_eur($b['custom_total_offer']) ?>
                                <span class="badge badge-green" style="vertical-align:middle;">-<?= (int)$b['discount_percent'] ?>% VIP</span>
                            </div>
                        <?php else: ?>
                            <div class="serif" style="font-size:30px;font-weight:700;color:var(--text-ivory);margin:4px 0 8px;">
                                <?= format_eur($b['total_estimated']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($b['concierge_notes'])): ?>
                            <p style="font-size:13px;color:var(--text-sand);margin-bottom:14px;line-height:1.55;">
                                “<?= e($b['concierge_notes']) ?>”
                            </p>
                        <?php endif; ?>

                        <?php if ($b['status'] === 'offerta_inviata'): ?>
                            <form method="POST" action="/area-clienti/bookings/<?= (int)$b['id'] ?>/accept">
                                <button type="submit" class="btn btn-gold" style="width:100%;">
                                    ✓ Accetta Proposta & Conferma Dossier
                                </button>
                            </form>
                        <?php elseif (in_array($b['status'], ['confermata', 'pagata'], true)): ?>
                            <div style="text-align:center;padding:8px;border-radius:10px;background:rgba(52,211,153,0.15);color:#6ee7b7;font-size:13px;font-weight:600;">
                                ✓ Dossier Confermato con i Partner
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
