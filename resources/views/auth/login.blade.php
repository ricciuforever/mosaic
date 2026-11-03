<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding-top:44px;padding-bottom:76px;">
    <div style="max-width:1060px;margin:0 auto;">
        <div style="text-align:center;margin-bottom:36px;">
            <span class="badge badge-gold" style="margin-bottom:10px;">Autenticazione Enterprise & Matrice Spatie RBAC</span>
            <h1 class="serif" style="font-size:44px;font-weight:500;margin-bottom:8px;">Accesso Riservato & Switch Ruolo</h1>
            <p style="font-size:15px;color:var(--text-sand);max-width:680px;margin:0 auto;">
                Seleziona un profilo RBAC preconfigurato per testare immediatamente permessi, viste e workflow dedicati, oppure accedi con le tue credenziali.
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div style="background:rgba(248,113,113,0.12);border:1px solid rgba(248,113,113,0.4);color:#fca5a5;padding:14px 20px;border-radius:12px;margin-bottom:24px;text-align:center;">
                Credenziali non valide. Utilizza uno dei profili RBAC rapidi qui sotto.
            </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1.35fr 0.85fr;gap:32px;align-items:start;">
            <!-- Left: 1-Click RBAC Demo Cards -->
            <div style="display:grid;gap:14px;">
                <h2 class="serif" style="font-size:26px;color:var(--gold-bright);margin-bottom:4px;">Accesso Rapido 1-Click per Ruolo RBAC</h2>
                <?php foreach ($demoUsers as $du):
                    $roleNames = $du->getRoleNames();
                    $permCount = count($du->getPermissionNames());
                ?>
                    <form method="POST" action="/login" style="background:var(--bg-card);border:1px solid var(--border-subtle);border-radius:16px;padding:18px 20px;display:flex;justify-content:space-between;align-items:center;gap:16px;transition:border-color 0.2s;">
                        <input type="hidden" name="demo_email" value="<?= e($du->email) ?>">
                        <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                        <div style="display:flex;align-items:center;gap:14px;">
                            <div style="width:46px;height:46px;border-radius:50%;background:var(--gold-muted);border:1px solid var(--gold);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--gold-bright);">
                                <?= e($du->avatar_initials) ?>
                            </div>
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:3px;">
                                    <strong style="font-size:15.5px;"><?= e($du->name) ?></strong>
                                    <?php foreach ($roleNames as $rn): ?>
                                        <span class="badge badge-gold"><?= e($rn) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div style="font-size:12.5px;color:var(--text-sand);">
                                    <?= e($du->email) ?> · <?= e($du->company_name) ?> · <strong><?= $permCount ?> permessi attivi</strong>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-gold btn-sm">
                            Entra come <?= e($roleNames[0] ?? 'Utente') ?> →
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>

            <!-- Right: Standard Credentials Login -->
            <div style="background:var(--bg-card);border:1px solid var(--gold-border);border-radius:20px;padding:28px;box-shadow:var(--shadow-luxury);">
                <h2 class="serif" style="font-size:26px;color:var(--text-ivory);margin-bottom:16px;">Login con Credenziali</h2>
                <form method="POST" action="/login">
                    <input type="hidden" name="redirect" value="<?= e($redirect) ?>">
                    <div style="margin-bottom:16px;">
                        <label class="form-label" for="login-email">Email Istituzionale</label>
                        <input type="email" name="email" id="login-email" class="form-input" value="admin@mosaic.digital" required>
                    </div>
                    <div style="margin-bottom:20px;">
                        <label class="form-label" for="login-password">Codice / Password *</label>
                        <input type="password" name="password" id="login-password" class="form-input" placeholder="Inserisci password (es. Mosaic2026!)" required>
                    </div>
                    <button type="submit" class="btn btn-gold" style="width:100%;">
                        Accedi alla Piattaforma →
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
