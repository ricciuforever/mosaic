<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding:90px 24px;text-align:center;">
    <div style="max-width:640px;margin:0 auto;background:var(--bg-card);border:1px solid rgba(248,113,113,0.45);border-radius:24px;padding:42px;box-shadow:var(--shadow-luxury);">
        <span class="badge badge-red" style="margin-bottom:14px;">HTTP 403 · Accesso Negato da Spatie RBAC</span>
        <h1 class="serif" style="font-size:40px;font-weight:600;margin-bottom:12px;">Autorizzazione Insufficiente</h1>
        <p style="font-size:15px;color:var(--text-sand);margin-bottom:28px;">
            <?= e($message ?? 'Il ruolo attualmente attivo non dispone dei permessi necessari per accedere a questa sezione.') ?>
        </p>
        <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
            <a href="/login" class="btn btn-gold">Cambia Ruolo RBAC →</a>
            <a href="/" class="btn btn-ghost">Torna alla Home</a>
        </div>
    </div>
</div>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
