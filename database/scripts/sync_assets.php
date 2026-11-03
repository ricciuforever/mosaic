<?php
/**
 * Scarica e sincronizza gli asset multimediali del prototipo Mosaic
 * e genera le illustrazioni fotografiche per la categoria Jet Privati.
 */

$base = 'https://mosaic-six-mu.vercel.app';
$rootDir = dirname(__DIR__, 2);

$slugs = [
    'villa-smeralda-porto-cervo', 'villa-pevero-bay', 'tenuta-arzachena', 'villa-ginestra-positano',
    'palazzo-ravello', 'villa-lariana-bellagio', 'villa-cernobbio', 'villa-marinella-porto-rotondo',
    'villa-tramonto-praiano', 'villa-varenna',
    'ferrari-sf90-stradale', 'lamborghini-huracan-evo-spyder', 'range-rover-autobiography',
    'porsche-911-turbo-cabrio', 'maserati-grancabrio-trofeo', 'bentley-continental-gtc',
    'mercedes-classe-g-63', 'rolls-royce-cullinan', 'aston-martin-db12-volante', 'ferrari-roma-spider',
    'ferretti-680', 'cranchi-43', 'pardo-50', 'sanlorenzo-sl78', 'gozzo-apreamare-38',
    'riva-aquarama-super', 'vandutch-40-como', 'pershing-7x', 'wallytender-48', 'frauscher-858-fantom',
    'gulfstream-g650er-olbia', 'pilatus-pc-24-costa-smeralda', 'agustawestland-aw139-heli-vip',
    'bombardier-challenger-350-napoli', 'airbus-ach145-positano-capri', 'cessna-citation-longitude-como'
];

$urls = [
    '/images/logo-mosaic.png' => $rootDir . '/public/images/logo-mosaic.png',
    '/images/hero.jpg'        => $rootDir . '/public/images/hero.jpg',
    '/images/og.jpg'          => $rootDir . '/public/images/og.jpg',
];

foreach ($slugs as $s) {
    for ($i = 1; $i <= 4; $i++) {
        $urls["/images/products/$s/$i.webp"] = $rootDir . "/public/images/products/$s/$i.webp";
    }
}

$mh = curl_multi_init();
$ok = 0;

foreach (array_chunk($urls, 25, true) as $chunk) {
    $handles = [];
    foreach ($chunk as $u => $dest) {
        if (file_exists($dest) && filesize($dest) > 200) {
            $ok++;
            continue;
        }
        $dir = dirname($dest);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $ch = curl_init($base . $u);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => 'MosaicAssetSync/1.0'
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$dest] = $ch;
    }
    if (!empty($handles)) {
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh, 0.1);
        } while ($running > 0);

        foreach ($handles as $dest => $ch) {
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $body = curl_multi_getcontent($ch);
            if ($code === 200 && strlen($body) > 200) {
                file_put_contents($dest, $body);
                $ok++;
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
    }
}
curl_multi_close($mh);

// Genera asset SVG ad alta definizione per i 6 Jet / Elicotteri VIP
$jets = [
    'gulfstream-g650er-olbia' => ['title' => 'Gulfstream G650ER', 'sub' => 'Heavy Jet · Olbia Eccelsa Aviation', 'accent' => '#c89c54'],
    'pilatus-pc-24-costa-smeralda' => ['title' => 'Pilatus PC-24 Super Versatile', 'sub' => 'Super Light Jet · Costa Smeralda', 'accent' => '#d8b273'],
    'agustawestland-aw139-heli-vip' => ['title' => 'Leonardo AW139 VIP Heli', 'sub' => 'Heli-Transfer · Porto Cervo & Pevero', 'accent' => '#b6813e'],
    'bombardier-challenger-350-napoli' => ['title' => 'Bombardier Challenger 350', 'sub' => 'Super Midsize Jet · Costiera & Capri', 'accent' => '#c6994f'],
    'airbus-ach145-positano-capri' => ['title' => 'Airbus ACH145 Private Edition', 'sub' => 'Elicottero Bimotore · Positano & Ravello', 'accent' => '#e0b970'],
    'cessna-citation-longitude-como' => ['title' => 'Cessna Citation Longitude', 'sub' => 'Midsize Jet & Idrovolante · Milano / Como', 'accent' => '#c89c54'],
];

$viewLabels = ['Exterior Tarmac Sunset', 'VIP Cabin Lounge', 'Cockpit & Avionics', 'Private Terminal Arrival'];

foreach ($jets as $slug => $meta) {
    $dir = $rootDir . "/public/images/products/$slug";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    for ($i = 1; $i <= 4; $i++) {
        $view = htmlspecialchars($viewLabels[$i - 1], ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($meta['title'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $safeSub = htmlspecialchars($meta['sub'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" width="1200" height="800">
  <defs>
    <linearGradient id="bg{$i}" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0b1019"/>
      <stop offset="50%" stop-color="#141d2b"/>
      <stop offset="100%" stop-color="#090c12"/>
    </linearGradient>
    <radialGradient id="glow{$i}" cx="65%" cy="35%" r="60%">
      <stop offset="0%" stop-color="{$meta['accent']}" stop-opacity="0.28"/>
      <stop offset="55%" stop-color="{$meta['accent']}" stop-opacity="0.06"/>
      <stop offset="100%" stop-color="#0a0d14" stop-opacity="0"/>
    </radialGradient>
    <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" stop-color="#e6c68f"/>
      <stop offset="50%" stop-color="#c89c54"/>
      <stop offset="100%" stop-color="#9a6a30"/>
    </linearGradient>
  </defs>
  <rect width="1200" height="800" fill="url(#bg{$i})"/>
  <rect width="1200" height="800" fill="url(#glow{$i})"/>
  <g opacity="0.12" stroke="#c6a868" stroke-width="1">
    <line x1="0" y1="560" x2="1200" y2="560"/>
    <line x1="0" y1="620" x2="1200" y2="620"/>
    <line x1="200" y1="560" x2="50" y2="800"/>
    <line x1="600" y1="560" x2="600" y2="800"/>
    <line x1="1000" y1="560" x2="1150" y2="800"/>
  </g>
  <g transform="translate(600, 340)" fill="none" stroke="url(#gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M-320,30 C-180,-10 -40,-28 180,-28 C260,-28 310,-12 340,8 C310,24 250,32 160,32 L-280,32 Z" fill="#182232" fill-opacity="0.85"/>
    <path d="M-60,-24 L-160,-130 L-95,-130 L30,-26 Z" fill="#1f2b3d"/>
    <path d="M-280,28 L-340,-68 L-285,-68 L-220,24 Z" fill="#c89c54" fill-opacity="0.25"/>
    <circle cx="-80" cy="-2" r="7" fill="#e6c68f"/>
    <circle cx="-40" cy="-2" r="7" fill="#e6c68f"/>
    <circle cx="0" cy="-2" r="7" fill="#e6c68f"/>
    <circle cx="40" cy="-2" r="7" fill="#e6c68f"/>
    <circle cx="80" cy="-2" r="7" fill="#e6c68f"/>
    <circle cx="120" cy="-2" r="7" fill="#e6c68f"/>
    <path d="M210,-20 L275,-6 L210,-4 Z" fill="#e6c68f" fill-opacity="0.45"/>
  </g>
  <text x="80" y="660" fill="#f3efe6" font-family="Georgia, serif" font-size="44" font-weight="600">{$safeTitle}</text>
  <text x="80" y="705" fill="#d8b273" font-family="sans-serif" font-size="20" letter-spacing="2">{$safeSub} · {$view}</text>
</svg>
SVG;
        file_put_contents("$dir/$i.svg", $svg);
    }
}

echo "Assets synced: $ok remote files + 24 jet SVG visuals.\n";
