<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Product;

class AiConciergeService
{
    public static function orchestrate(string $prompt, array $context = []): array
    {
        $cleanPrompt = trim($prompt);
        if ($cleanPrompt === '') {
            $cleanPrompt = '5 notti in Costa Smeralda per 6 ospiti con villa vista mare, supercar e yacht privato';
        }

        $parsed = self::parseIntentSemantic($cleanPrompt);

        // If GEMINI_API_KEY is available in server environment, enrich via Gemini 2.5 Flash
        $apiKey = env('GEMINI_API_KEY', '');
        $engine = 'mosaic-semantic-nlp-v2';
        $aiNarrative = null;

        if (is_string($apiKey) && strlen(trim($apiKey)) > 10) {
            $geminiResult = self::callGeminiFlash(trim($apiKey), $cleanPrompt, $parsed);
            if ($geminiResult) {
                $engine = 'gemini-2.5-flash';
                if (!empty($geminiResult['destination']) && in_array($geminiResult['destination'], ['costa-smeralda', 'costiera-amalfitana', 'lago-di-como'], true)) {
                    $parsed['destination'] = $geminiResult['destination'];
                }
                if (!empty($geminiResult['days']) && (int)$geminiResult['days'] >= 1 && (int)$geminiResult['days'] <= 30) {
                    $parsed['days'] = (int)$geminiResult['days'];
                }
                if (!empty($geminiResult['adults']) && (int)$geminiResult['adults'] >= 1) {
                    $parsed['adults'] = (int)$geminiResult['adults'];
                }
                if (!empty($geminiResult['categories']) && is_array($geminiResult['categories'])) {
                    $validCats = array_values(array_intersect($geminiResult['categories'], ['villa', 'auto', 'yacht', 'jet']));
                    if (!empty($validCats)) {
                        $parsed['categories'] = $validCats;
                    }
                }
                if (!empty($geminiResult['reply'])) {
                    $aiNarrative = $geminiResult['reply'];
                }
            }
        }

        $destObj = Destination::findBySlug($parsed['destination']) ?? Destination::findBySlug('costa-smeralda');
        $destName = $destObj['name'] ?? 'Costa Smeralda';

        $selectedProducts = [];
        $totalEstimated = 0;
        $selectedCodesByCat = [];

        foreach ($parsed['categories'] as $cat) {
            $candidates = Product::filter([
                'category' => $cat,
                'destination' => $parsed['destination'],
            ]);

            if (empty($candidates)) {
                $candidates = Product::filter(['category' => $cat]);
            }

            $chosen = self::pickBestCandidate($candidates, $cleanPrompt, $parsed['adults'], $cat);
            if ($chosen) {
                $daysForCat = ($cat === 'jet') ? 1 : (($cat === 'yacht' && $parsed['days'] > 3) ? 2 : $parsed['days']);
                $subtotal = $chosen['price'] * $daysForCat;
                $totalEstimated += $subtotal;
                $selectedCodesByCat[$cat] = $chosen['code'];
                $selectedProducts[] = [
                    'id' => $chosen['id'],
                    'code' => $chosen['code'],
                    'slug' => $chosen['slug'],
                    'category' => $chosen['category'],
                    'title' => $chosen['title'],
                    'subtitle' => $chosen['subtitle'],
                    'location' => $chosen['location'],
                    'price' => $chosen['price'],
                    'unit' => $chosen['unit'],
                    'days' => $daysForCat,
                    'subtotal' => $subtotal,
                    'image' => $chosen['images'][0] ?? '/images/hero.jpg',
                    'supplier' => $chosen['supplier_company'] ?? 'Partner Verificato',
                    'url' => '/prodotto/' . $chosen['slug'],
                ];
            }
        }

        $queryArgs = [
            'dest' => $parsed['destination'],
            'cats' => implode(',', $parsed['categories']),
            'days' => $parsed['days'],
            'adults' => $parsed['adults'],
            'children' => $parsed['children'],
            'start' => $parsed['start_date'],
            'end' => $parsed['end_date'],
            'preselect' => implode(',', array_values($selectedCodesByCat)),
            'ai' => 1,
        ];
        $composeUrl = '/compose?' . http_build_query($queryArgs);

        if (!$aiNarrative) {
            $titles = array_map(fn($p) => '«' . $p['title'] . '»', $selectedProducts);
            $catListStr = implode(', ', $titles);
            $aiNarrative = sprintf(
                "Ho composto un itinerario sartoriale di %d %s in %s per %d %s (%s – %s). La selezione abbina %s, tutti con disponibilità prioritaria presso i nostri partner certificati. Stima complessiva dell'esperienza: €%s.",
                $parsed['days'],
                $parsed['days'] === 1 ? 'giorno' : 'notti',
                $destName,
                $parsed['adults'],
                $parsed['adults'] === 1 ? 'ospite' : 'ospiti',
                $parsed['start_date'],
                $parsed['end_date'],
                $catListStr,
                number_format($totalEstimated, 0, ',', '.')
            );
        }

        return [
            'ok' => true,
            'engine' => $engine,
            'prompt' => $cleanPrompt,
            'reply' => $aiNarrative,
            'intent' => [
                'destination_slug' => $parsed['destination'],
                'destination_name' => $destName,
                'days' => $parsed['days'],
                'adults' => $parsed['adults'],
                'children' => $parsed['children'],
                'start_date' => $parsed['start_date'],
                'end_date' => $parsed['end_date'],
                'categories' => $parsed['categories'],
            ],
            'recommendations' => $selectedProducts,
            'total_estimated' => $totalEstimated,
            'compose_url' => $composeUrl,
        ];
    }

    public static function parseIntentSemantic(string $prompt): array
    {
        $low = mb_strtolower($prompt, 'UTF-8');

        // 1. Destination detection
        $destination = 'costa-smeralda';
        if (preg_match('/(amalfi|positano|ravello|capri|sorrento|praiano|conca|campania|costiera|napoli|salerno)/u', $low)) {
            $destination = 'costiera-amalfitana';
        } elseif (preg_match('/(como|bellagio|cernobbio|tremezzo|varenna|menaggio|lario|lombardia)/u', $low)) {
            $destination = 'lago-di-como';
        } elseif (preg_match('/(sardegna|smeralda|cervo|volpe|rotondo|olbia|pevero|romazzino|pantogia|maddalena|gallura)/u', $low)) {
            $destination = 'costa-smeralda';
        }

        // 2. Duration detection
        $days = 4;
        if (preg_match('/(\d+)\s*(nott[ei]|giorn[oi]|gg)/u', $low, $m)) {
            $days = max(1, min(21, (int)$m[1]));
        } elseif (strpos($low, 'settimana') !== false) {
            $days = 7;
        } elseif (strpos($low, 'weekend lungo') !== false || strpos($low, 'fine settimana lungo') !== false) {
            $days = 4;
        } elseif (strpos($low, 'weekend') !== false || strpos($low, 'fine settimana') !== false) {
            $days = 3;
        }

        // 3. Guests detection
        $adults = 4;
        $children = 0;
        if (preg_match('/(\d+)\s*(ospit[ei]|person[ae]|adult[oi]|amic[oi]|passegger[oi])/u', $low, $m)) {
            $adults = max(1, min(20, (int)$m[1]));
        } elseif (strpos($low, 'coppia') !== false || strpos($low, 'due persone') !== false || strpos($low, 'anniversario') !== false || strpos($low, 'romantico') !== false) {
            $adults = 2;
        } elseif (strpos($low, 'famiglia') !== false) {
            $adults = 4;
            $children = 2;
        }
        if (preg_match('/(\d+)\s*(bambin[oi]|ragazz[oi])/u', $low, $mb)) {
            $children = max(0, min(10, (int)$mb[1]));
        }

        // 4. Categories detection
        $categories = [];
        if (preg_match('/(villa|ville|dimora|tenuta|palazzo|alloggio|piscina|camere|dormire|notte|notti|soggiorno)/u', $low)) {
            $categories[] = 'villa';
        }
        if (preg_match('/(auto|supercar|macchina|ferrari|lamborghini|rolls|cullinan|porsche|bentley|maserati|aston|range rover|mercedes|suv|spider|cabrio|chauffeur)/u', $low)) {
            $categories[] = 'auto';
        }
        if (preg_match('/(yacht|barca|barche|motoscafo|riva|ferretti|pershing|sanlorenzo|pardo|wally|gozzo|cranchi|frauscher|vandutch|mare|crociera|skipper| tender)/u', $low)) {
            $categories[] = 'yacht';
        }
        if (preg_match('/(jet|elicottero|volo|aereo|heli|gulfstream|pilatus|challenger|agusta|aw139|ach145|citation|atterraggio|linate|eccelsa)/u', $low)) {
            $categories[] = 'jet';
        }

        if (empty($categories)) {
            $categories = ['villa', 'auto', 'yacht'];
        }

        // 5. Date window formatting
        $monthLabel = 'Lug 2026';
        if (strpos($low, 'giugno') !== false) $monthLabel = 'Giu 2026';
        elseif (strpos($low, 'agosto') !== false) $monthLabel = 'Ago 2026';
        elseif (strpos($low, 'settembre') !== false) $monthLabel = 'Set 2026';
        elseif (strpos($low, 'maggio') !== false) $monthLabel = 'Mag 2026';

        $startDay = 15;
        $endDay = min(30, $startDay + $days);
        $startDate = sprintf('%02d %s', $startDay, $monthLabel);
        $endDate = sprintf('%02d %s', $endDay, $monthLabel);

        return [
            'destination' => $destination,
            'days' => $days,
            'adults' => $adults,
            'children' => $children,
            'categories' => array_values(array_unique($categories)),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    private static function pickBestCandidate(array $candidates, string $prompt, int $adults, string $category): ?array
    {
        if (empty($candidates)) {
            return null;
        }
        $low = mb_strtolower($prompt, 'UTF-8');

        $best = null;
        $bestScore = -999;

        foreach ($candidates as $c) {
            $score = 0;
            $titleLow = mb_strtolower($c['title'], 'UTF-8');
            $subLow = mb_strtolower($c['subtitle'] ?? '', 'UTF-8');
            $locLow = mb_strtolower($c['location'] ?? '', 'UTF-8');

            // Keyword match on brand/model/locality
            foreach (explode(' ', preg_replace('/[^a-z0-9àèéìòù]+/u', ' ', $titleLow . ' ' . $locLow)) as $word) {
                if ( mb_strlen($word) >= 4 && strpos($low, $word) !== false) {
                    $score += 25;
                }
            }

            if ($category === 'jet') {
                if ((strpos($low, 'elicottero') !== false || strpos($low, 'heli') !== false) && strpos($subLow, 'elicottero') !== false) {
                    $score += 40;
                } elseif (strpos($low, 'jet') !== false && strpos($subLow, 'jet') !== false) {
                    $score += 30;
                }
            }

            if ($c['capacity'] >= $adults) {
                $score += 15;
            }
            if (!empty($c['featured'])) {
                $score += 10;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $c;
            }
        }

        return $best ?? $candidates[0];
    }

    private static function callGeminiFlash(string $apiKey, string $prompt, array $fallbackParsed): ?array
    {
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $sysInstruction = "Sei il Senior Private Concierge di MOSAIC, piattaforma d'élite per esperienze di lusso in Italia. "
            . "Estrai dal prompt dell'utente un oggetto JSON con le chiavi: "
            . "destination ('costa-smeralda' | 'costiera-amalfitana' | 'lago-di-como'), "
            . "days (intero 1..21), adults (intero 1..20), "
            . "categories (array di stringhe scelte tra 'villa','auto','yacht','jet'), "
            . "reply (risposta breve, elegante e autorevole in italiano, max 2 frasi). Rispondi SOLO in JSON valido.";

        $body = [
            'systemInstruction' => [
                'parts' => [['text' => $sysInstruction]],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $prompt]],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'responseMimeType' => 'application/json',
            ],
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-goog-api-key: ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_TIMEOUT => 6,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200 || !$res) {
            return null;
        }

        $decoded = json_decode($res, true);
        $text = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!$text) {
            return null;
        }

        $json = json_decode($text, true);
        return is_array($json) ? $json : null;
    }
}
