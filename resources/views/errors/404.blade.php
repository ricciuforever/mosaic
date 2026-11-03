<?php require resource_path('views/layouts/header.blade.php'); ?>

<div class="container" style="padding:90px 24px;text-align:center;">
    <div style="max-width:640px;margin:0 auto;background:var(--bg-card);border:1px solid var(--gold-border);border-radius:24px;padding:42px;box-shadow:var(--shadow-luxury);">
        <span class="badge badge-gold" style="margin-bottom:14px;">HTTP 404 · Risorsa Non Trovata</span>
        <h1 class="serif" style="font-size:40px;font-weight:600;margin-bottom:12px;">Pagina o Esperienza Non Disponibile</h1>
        <p style="font-size:15px;color:var(--text-sand);margin-bottom:28px;">
            <?= e($message ?? 'L\'indirizzo richiesto non corrisponde a un\'esperienza attiva nel catalogo MOSAIC.') ?>
        </p>
        <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
            <a href="/compose" class="btn btn-gold">Esplora il Compositore →</a>
            <a href="/" class="btn btn-ghost">Torna alla Home</a>
        </div>
    </div>
</div>

<?php require resource_path('views/layouts/footer.blade.php'); ?>
