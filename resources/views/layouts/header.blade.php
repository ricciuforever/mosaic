<?php
use App\Core\Auth;
use App\Models\Setting;

$currentUser = Auth::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$portalMode = $supplierPortalMode ?? Setting::get('supplier_portal_mode', 'standby');
?>
<!DOCTYPE html>
<html lang="it" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'MOSAIC · Luxury Experience Concierge') ?></title>
    <meta name="description" content="Compone soggiorni su misura in Italia combinando ville private, supercar, yacht con equipaggio e jet privati in Costa Smeralda, Costiera Amalfitana e Lago di Como.">
    <link rel="icon" type="image/png" href="/images/logo-mosaic.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" crossorigin="">
    <style>
        :root {
            --bg-void: #070a0f;
            --bg-deep: #0b0f17;
            --bg-card: #111723;
            --bg-elevated: #172030;
            --bg-glass: rgba(17, 23, 35, 0.86);
            --gold: #c89b52;
            --gold-bright: #e4be7a;
            --gold-muted: rgba(200, 155, 82, 0.18);
            --gold-border: rgba(200, 155, 82, 0.34);
            --text-ivory: #f5f1e8;
            --text-sand: #c5beb0;
            --text-muted: #868e9e;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(200, 155, 82, 0.48);
            --success: #34d399;
            --warning: #fbbf24;
            --danger: #f87171;
            --info: #60a5fa;
            --font-serif: 'Cormorant Garamond', Georgia, serif;
            --font-sans: 'Manrope', -apple-system, BlinkMacSystemFont, sans-serif;
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-pill: 9999px;
            --shadow-luxury: 0 24px 60px rgba(0, 0, 0, 0.65);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; background-color: var(--bg-void); color: var(--text-ivory); font-family: var(--font-sans); }
        body { min-height: 100vh; display: flex; flex-direction: column; background: radial-gradient(circle at 50% 0%, #151d2c 0%, var(--bg-void) 65%); color: var(--text-ivory); line-height: 1.55; overflow-x: hidden; }
        a { color: inherit; text-decoration: none; transition: color 0.2s ease, opacity 0.2s ease; }
        button, input, select, textarea { font-family: inherit; color: inherit; }
        button { cursor: pointer; border: none; background: none; }
        img { max-width: 100%; display: block; }

        .serif { font-family: var(--font-serif); }
        .container { width: 100%; max-width: 1380px; margin: 0 auto; padding: 0 24px; }

        /* Top Role Bar & Header */
        .rbac-bar { background: rgba(7, 10, 15, 0.95); border-bottom: 1px solid var(--border-subtle); font-size: 12px; padding: 7px 0; z-index: 90; }
        .rbac-bar-inner { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .rbac-switcher { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .rbac-chip { padding: 3px 10px; border-radius: var(--radius-pill); border: 1px solid var(--border-subtle); background: rgba(255,255,255,0.03); color: var(--text-sand); font-size: 11px; font-weight: 500; transition: all 0.2s; }
        .rbac-chip:hover, .rbac-chip.active { border-color: var(--gold); background: var(--gold-muted); color: var(--gold-bright); }

        .site-header { position: sticky; top: 0; z-index: 85; background: rgba(11, 15, 23, 0.88); backdrop-filter: blur(18px); border-bottom: 1px solid var(--border-subtle); }
        .header-inner { height: 74px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand-logo { display: flex; align-items: center; gap: 12px; font-family: var(--font-serif); font-size: 26px; font-weight: 600; letter-spacing: 0.14em; color: var(--text-ivory); }
        .brand-logo img { height: 34px; width: auto; object-fit: contain; }
        .nav-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .nav-link { padding: 8px 14px; border-radius: var(--radius-pill); font-size: 13.5px; font-weight: 500; color: var(--text-sand); transition: all 0.2s; display: inline-flex; align-items: center; gap: 7px; }
        .nav-link:hover, .nav-link.active { color: var(--text-ivory); background: rgba(255, 255, 255, 0.06); }
        .nav-link.gold { border: 1px solid var(--gold-border); background: var(--gold-muted); color: var(--gold-bright); }
        .nav-link.gold:hover { background: var(--gold); color: #090c12; }

        .status-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
        .status-dot.standby { background: var(--warning); box-shadow: 0 0 8px var(--warning); }
        .status-dot.active { background: var(--success); box-shadow: 0 0 8px var(--success); }

        /* Buttons & Badges */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 22px; border-radius: var(--radius-pill); font-size: 13.5px; font-weight: 600; transition: all 0.22s ease; white-space: nowrap; }
        .btn-gold { background: linear-gradient(135deg, #dfba73 0%, #c49347 100%); color: #080b11; box-shadow: 0 8px 24px rgba(200, 155, 82, 0.28); }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(200, 155, 82, 0.42); filter: brightness(1.05); }
        .btn-outline { border: 1px solid var(--gold-border); background: rgba(200, 155, 82, 0.08); color: var(--gold-bright); }
        .btn-outline:hover { border-color: var(--gold-bright); background: rgba(200, 155, 82, 0.18); }
        .btn-ghost { border: 1px solid var(--border-subtle); background: rgba(255, 255, 255, 0.04); color: var(--text-sand); }
        .btn-ghost:hover { border-color: rgba(255, 255, 255, 0.22); color: var(--text-ivory); background: rgba(255, 255, 255, 0.08); }
        .btn-sm { padding: 7px 14px; font-size: 12px; }

        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: var(--radius-pill); font-size: 11px; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }
        .badge-gold { background: rgba(200, 155, 82, 0.18); color: var(--gold-bright); border: 1px solid var(--gold-border); }
        .badge-green { background: rgba(52, 211, 153, 0.15); color: #6ee7b7; border: 1px solid rgba(52, 211, 153, 0.35); }
        .badge-amber { background: rgba(251, 191, 36, 0.15); color: #fcd34d; border: 1px solid rgba(251, 191, 36, 0.35); }
        .badge-blue { background: rgba(96, 165, 250, 0.15); color: #93c5fd; border: 1px solid rgba(96, 165, 250, 0.35); }
        .badge-red { background: rgba(248, 113, 113, 0.15); color: #fca5a5; border: 1px solid rgba(248, 113, 113, 0.35); }

        /* Modern Web Guidance: <dialog closedby="any"> & prevent-page-scroll-under-modal-dialog */
        dialog.luxury-dialog {
            margin: auto;
            width: min(92vw, 760px);
            max-height: 88vh;
            background: var(--bg-card);
            color: var(--text-ivory);
            border: 1px solid var(--gold-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-luxury);
            padding: 0;
            overflow: auto;
            overscroll-behavior: contain;
        }
        dialog.luxury-dialog:modal {
            overscroll-behavior: contain;
        }
        dialog.luxury-dialog::backdrop {
            background: rgba(4, 7, 12, 0.78);
            backdrop-filter: blur(8px);
            overflow: hidden;
            overscroll-behavior: contain;
        }
        .dialog-header {
            padding: 20px 26px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.02);
        }
        .dialog-body {
            padding: 24px 26px;
            overflow-y: auto;
            max-height: calc(88vh - 80px);
        }

        /* Modern Web Guidance: Responsive Table with Container Queries */
        .table-wrapper {
            overflow: auto;
            max-inline-size: 100%;
            container-type: inline-size;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            background: var(--bg-card);
        }
        .table-wrapper:focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
        }
        .luxury-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13.5px;
        }
        .luxury-table caption {
            text-align: left;
            padding: 16px 20px;
            font-family: var(--font-serif);
            font-size: 20px;
            font-weight: 600;
            color: var(--gold-bright);
            border-bottom: 1px solid var(--border-subtle);
        }
        .luxury-table thead {
            position: sticky;
            inset-block-start: 0;
            z-index: 1;
        }
        .luxury-table thead th {
            position: sticky;
            top: 0;
            background: var(--bg-elevated);
            color: var(--text-sand);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 600;
            text-align: left;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border-subtle);
            z-index: 1;
        }
        .luxury-table tbody tr {
            border-bottom: 1px solid var(--border-subtle);
            transition: background 0.15s ease;
        }
        .luxury-table tbody tr:hover {
            background: rgba(200, 155, 82, 0.05);
        }
        .luxury-table td {
            padding: 15px 18px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-subtle);
        }
        .numeric {
            font-variant-numeric: tabular-nums;
            text-align: right;
        }
        @container (width < 600px) {
            .luxury-table, .luxury-table thead, .luxury-table tbody, .luxury-table tr, .luxury-table th, .luxury-table td {
                display: block;
            }
            .luxury-table thead {
                display: none;
            }
            .luxury-table tbody tr {
                padding: 12px 14px;
                margin-bottom: 10px;
            }
            .luxury-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                padding: 8px 6px;
                text-align: right;
                border-bottom: 1px dashed rgba(255, 255, 255, 0.06);
            }
            .luxury-table td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 11px;
                text-transform: uppercase;
                color: var(--text-muted);
                text-align: left;
            }
        }

        /* Floating AI Concierge Widget */
        .ai-fab {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 95;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 20px;
            border-radius: var(--radius-pill);
            background: linear-gradient(135deg, #172030 0%, #0e1420 100%);
            border: 1px solid var(--gold);
            color: var(--gold-bright);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.75), 0 0 22px rgba(200, 155, 82, 0.25);
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .ai-fab:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 46px rgba(0, 0, 0, 0.85), 0 0 30px rgba(200, 155, 82, 0.45);
        }
        .ai-drawer {
            position: fixed;
            right: 24px;
            bottom: 84px;
            width: min(92vw, 410px);
            max-height: 76vh;
            background: rgba(15, 21, 32, 0.97);
            backdrop-filter: blur(20px);
            border: 1px solid var(--gold-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-luxury);
            z-index: 96;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        .ai-drawer.open { display: flex; }
        .ai-drawer-messages {
            padding: 18px;
            overflow-y: auto;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 390px;
        }
        .ai-msg {
            padding: 12px 15px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.5;
        }
        .ai-msg.assistant {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-subtle);
            color: var(--text-ivory);
        }
        .ai-msg.user {
            background: var(--gold-muted);
            border: 1px solid var(--gold-border);
            color: var(--gold-bright);
            align-self: flex-end;
        }

        /* Form Controls */
        .form-label { display: block; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-sand); margin-bottom: 6px; font-weight: 600; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-sm);
            padding: 11px 14px;
            color: var(--text-ivory);
            font-size: 13.5px;
            transition: border-color 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--gold);
            background: rgba(255, 255, 255, 0.07);
        }
        .form-select option { background: #111723; color: var(--text-ivory); }

        /* MapLibre GL Luxury Markers & Attribution (Prototype exact CSS) */
        .msc-pin {
            color: #e8e3d9;
            cursor: pointer;
            white-space: nowrap;
            background: #0c1017eb;
            border: 1px solid #ffffff24;
            border-radius: 20px;
            padding: 6px 13px;
            font-family: Manrope, sans-serif;
            font-size: 13px;
            font-weight: 600;
            transition: border-color .2s, color .2s;
            box-shadow: 0 6px 16px #0006;
        }
        .msc-pin--hover {
            color: #e6c68f;
            z-index: 2;
            border-color: #c6a868bf;
        }
        .msc-pin--active {
            color: #241704;
            z-index: 2;
            background: linear-gradient(#d0a45a, #b6813e);
            border-color: #ffecc880;
            font-weight: 700;
            position: relative;
            box-shadow: 0 8px 20px #9a6a3080;
        }
        .msc-pin--active:before {
            content: "";
            z-index: -1;
            background: #c69a4e99;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            animation: 2.6s ease-in-out infinite mscPinPulse;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .maplibregl-ctrl-attrib {
            border-radius: 10px 0 0;
            background: #0a0d14a6 !important;
        }
        .maplibregl-ctrl-attrib, .maplibregl-ctrl-attrib a {
            color: #aaa69cbf !important;
            font: 10px Manrope, sans-serif !important;
        }
        .maplibregl-ctrl-attrib-button {
            filter: invert(.8);
        }
        @keyframes mscPinPulse {
            0%, to { opacity: .55; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: .15; transform: translate(-50%, -50%) scale(1.9); }
        }

        @media (max-width: 900px) {
            .header-inner { height: auto; padding: 14px 0; flex-wrap: wrap; }
            .nav-links { width: 100%; justify-content: flex-start; overflow-x: auto; padding-bottom: 4px; }
        }
    </style>
</head>
<body>
    <!-- Top RBAC Quick Role Switcher Bar for Enterprise Testing -->
    <div class="rbac-bar">
        <div class="container rbac-bar-inner">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge badge-gold">RBAC Spatie Live</span>
                <?php if ($currentUser): ?>
                    <span style="color:var(--text-ivory);">
                        Connesso come: <strong><?= e($currentUser->name) ?></strong>
                        (<?= e(implode(', ', $currentUser->getRoleNames())) ?>)
                    </span>
                <?php else: ?>
                    <span style="color:var(--text-muted);">Sessione Ospite · Seleziona un ruolo RBAC in 1 click:</span>
                <?php endif; ?>
            </div>
            <div class="rbac-switcher">
                <?php
                $quickRoles = [
                    ['label' => 'Super Admin', 'email' => 'admin@mosaic.digital'],
                    ['label' => 'Concierge Manager', 'email' => 'concierge@mosaic.digital'],
                    ['label' => 'Fornitore/Partner', 'email' => 'fornitore@mosaic.digital'],
                    ['label' => 'Cliente VIP', 'email' => 'vip@mosaic.digital'],
                ];
                foreach ($quickRoles as $qr):
                    $isActive = $currentUser && strtolower($currentUser->email) === $qr['email'];
                ?>
                    <form method="POST" action="/login" style="display:inline;">
                        <input type="hidden" name="demo_email" value="<?= e($qr['email']) ?>">
                        <input type="hidden" name="redirect" value="<?= e($currentPath) ?>">
                        <button type="submit" class="rbac-chip <?= $isActive ? 'active' : '' ?>">
                            <?= $isActive ? '● ' : '' ?><?= e($qr['label']) ?>
                        </button>
                    </form>
                <?php endforeach; ?>
                <?php if ($currentUser): ?>
                    <a href="/logout" class="rbac-chip" style="color:var(--danger);">Esci</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Luxury Header -->
    <header class="site-header">
        <div class="container header-inner">
            <a href="/" class="brand-logo">
                <img src="/images/logo-mosaic.png" alt="MOSAIC">
                <span>MOSAIC</span>
            </a>

            <nav class="nav-links" aria-label="Menu Principale">
                <a href="/" class="nav-link <?= $currentPath === '/' ? 'active' : '' ?>">Esperienze</a>
                <a href="/compose" class="nav-link <?= str_starts_with($currentPath, '/compose') ? 'active' : '' ?>">
                    Componi Soggiorno
                </a>
                <a href="/portale-fornitore" class="nav-link <?= str_starts_with($currentPath, '/portale-fornitore') ? 'active' : '' ?>">
                    <span class="status-dot <?= $portalMode === 'active' ? 'active' : 'standby' ?>"></span>
                    Portale Fornitore
                    <span style="font-size:10px;opacity:0.75;">(<?= $portalMode === 'active' ? 'LIVE' : 'STANDBY' ?>)</span>
                </a>
                <a href="/area-clienti" class="nav-link <?= str_starts_with($currentPath, '/area-clienti') ? 'active' : '' ?>">
                    Area Clienti VIP
                </a>
                <a href="/admin" class="nav-link <?= str_starts_with($currentPath, '/admin') ? 'active' : '' ?>">
                    Console Admin
                </a>
                <a href="/richiesta" class="nav-link gold">
                    <span>Il tuo Dossier</span>
                    <span id="header-dossier-count" style="background:#080b11;color:var(--gold-bright);padding:1px 7px;border-radius:99px;font-size:11px;">0</span>
                </a>
            </nav>
        </div>
    </header>

    <main style="flex:1;">
