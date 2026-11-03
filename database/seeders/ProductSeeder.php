<?php

namespace Database\Seeders;

use App\Core\Database;

class ProductSeeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $destinations = Database::select("SELECT id, slug FROM destinations");
        $destMap = [];
        foreach ($destinations as $d) {
            $destMap[$d['slug']] = (int)$d['id'];
        }

        $suppliers = Database::select("SELECT id, company_name FROM suppliers");
        $supMap = [];
        foreach ($suppliers as $s) {
            $supMap[$s['company_name']] = (int)$s['id'];
        }

        $catalog = array (
  0 => 
  array (
    'id' => 'vl-01',
    'slug' => 'villa-smeralda-porto-cervo',
    'title' => 'Villa Smeralda',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 5500,
    'depositAmount' => 8000,
    'rating' => 4.9,
    'reviewsCount' => 27,
    'description' => 'Sospesa sulla baia di Porto Cervo, Villa Smeralda unisce l\'architettura mediterranea della Costa Smeralda a interni contemporanei firmati da uno studio milanese. Ogni camera si apre su terrazze private con vista sul mare color giada.

Il grande living con vetrate a tutta altezza scorre verso la piscina a sfioro e l\'area pranzo esterna in granito locale, per cene al tramonto servite dallo staff dedicato.

La posizione, a cinque minuti dalla Piazzetta e dal porto vecchio, la rende la base ideale per un\'esperienza completa tra mare, boutique e vita notturna.',
    'amenities' => 
    array (
      0 => 'Piscina a sfioro',
      1 => 'Vista mare panoramica',
      2 => 'Aria condizionata',
      3 => 'Wi-Fi ad alta velocità',
      4 => 'Palestra privata',
      5 => 'Cantina vini',
      6 => 'Parcheggio per 4 auto',
      7 => 'Sistema audio Sonos',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Governante giornaliera',
      1 => 'Concierge dedicato 7/7',
      2 => 'Cambio biancheria a giorni alterni',
      3 => 'Utenze e climatizzazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Transfer da/per aeroporto',
      2 => 'Baby sitting',
    ),
    'supplier' => 'Smeralda Estates',
    'map' => 
    array (
      'x' => 52,
      'y' => 40,
    ),
    'lnglat' => 
    array (
      0 => 9.529,
      1 => 41.133,
    ),
    'badge' => 'VISTA MARE',
    'bedrooms' => 6,
    'beds' => 7,
    'bathrooms' => 7,
    'maxGuests' => 12,
    'sqm' => 480,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Governante',
      1 => 'Giardiniere',
      2 => 'Concierge',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-smeralda-porto-cervo/1.webp',
      1 => '/images/products/villa-smeralda-porto-cervo/2.webp',
      2 => '/images/products/villa-smeralda-porto-cervo/3.webp',
      3 => '/images/products/villa-smeralda-porto-cervo/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '6 camere · 7 bagni · 480 m²',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 5500,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  1 => 
  array (
    'id' => 'vl-02',
    'slug' => 'villa-pevero-bay',
    'title' => 'Villa Pevero Bay',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 8500,
    'depositAmount' => 12000,
    'rating' => 5,
    'reviewsCount' => 14,
    'description' => 'Affacciata sul golfo del Pevero, questa tenuta di nuova costruzione è tra le proprietà più esclusive della costa: sette suite, spa privata con hammam e un parco di macchia mediterranea che digrada verso una caletta riservata.

Gli ambienti interni alternano pietra sarda, rovere e opere d\'arte contemporanea. La zona living si apre su una terrazza di 300 mq con piscina riscaldata e lounge esterna.

Lo staff residente, con maggiordomo e chef su richiesta, garantisce un servizio da hotel cinque stelle nella privacy assoluta di una casa privata.',
    'amenities' => 
    array (
      0 => 'Accesso privato al mare',
      1 => 'Spa con hammam',
      2 => 'Piscina riscaldata',
      3 => 'Cinema privato',
      4 => 'Eliporto convenzionato',
      5 => 'Wi-Fi ad alta velocità',
      6 => 'Cantina vini',
      7 => 'Suite indipendente per staff',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Maggiordomo residente',
      1 => 'Governante giornaliera',
      2 => 'Concierge dedicato 7/7',
      3 => 'Sicurezza notturna',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Sommelier per eventi',
      2 => 'Transfer in elicottero',
    ),
    'supplier' => 'Smeralda Estates',
    'map' => 
    array (
      'x' => 64,
      'y' => 48,
    ),
    'lnglat' => 
    array (
      0 => 9.546,
      1 => 41.121,
    ),
    'badge' => 'STAFF RESIDENTE',
    'bedrooms' => 7,
    'beds' => 9,
    'bathrooms' => 8,
    'maxGuests' => 14,
    'sqm' => 650,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante',
      2 => 'Giardiniere',
      3 => 'Sicurezza',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-pevero-bay/1.webp',
      1 => '/images/products/villa-pevero-bay/2.webp',
      2 => '/images/products/villa-pevero-bay/3.webp',
      3 => '/images/products/villa-pevero-bay/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '7 camere · 8 bagni · 650 m²',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 8500,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  2 => 
  array (
    'id' => 'vl-03',
    'slug' => 'tenuta-arzachena',
    'title' => 'Tenuta Arzachena',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Arzachena',
    'pricePerDay' => 2800,
    'depositAmount' => 4000,
    'rating' => 4.7,
    'reviewsCount' => 41,
    'description' => 'Uno stazzo gallurese ristrutturato con rigore filologico, immerso in dodici ettari di vigneti e sughere tra Arzachena e la costa. Il fascino della campagna sarda a quindici minuti dalle spiagge del Pevero.

Cinque camere in pietra locale, una corte interna con pergolato per lunghi pranzi estivi e una piscina in travertino affacciata sulle vigne di Vermentino della tenuta.

Ideale per chi cerca autenticità senza rinunciare al servizio: la cantina della proprietà organizza degustazioni private e cene con prodotti del territorio.',
    'amenities' => 
    array (
      0 => 'Piscina in travertino',
      1 => 'Vigneto e cantina propri',
      2 => 'Corte interna con pergolato',
      3 => 'Camino in pietra',
      4 => 'Aria condizionata',
      5 => 'Wi-Fi',
      6 => 'Barbecue in muratura',
      7 => 'Parcheggio privato',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Pulizie giornaliere',
      1 => 'Degustazione di benvenuto',
      2 => 'Utenze',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Cene in vigna (su richiesta)',
      2 => 'Transfer',
    ),
    'supplier' => 'Gallura Retreats',
    'map' => 
    array (
      'x' => 18,
      'y' => 42,
    ),
    'lnglat' => 
    array (
      0 => 9.405,
      1 => 41.068,
    ),
    'bedrooms' => 5,
    'beds' => 6,
    'bathrooms' => 5,
    'maxGuests' => 10,
    'sqm' => 380,
    'pool' => true,
    'seaView' => false,
    'staff' => 
    array (
      0 => 'Governante',
      1 => 'Cantiniere',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/tenuta-arzachena/1.webp',
      1 => '/images/products/tenuta-arzachena/2.webp',
      2 => '/images/products/tenuta-arzachena/3.webp',
      3 => '/images/products/tenuta-arzachena/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '5 camere · 5 bagni · 380 m²',
    'location' => 'Arzachena, Costa Smeralda',
    'price' => 2800,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'Villa con Piscina',
  ),
  3 => 
  array (
    'id' => 'vl-04',
    'slug' => 'villa-ginestra-positano',
    'title' => 'Villa Ginestra',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Positano',
    'pricePerDay' => 4200,
    'depositAmount' => 6000,
    'rating' => 4.9,
    'reviewsCount' => 33,
    'description' => 'Aggrappata alla roccia sopra Positano, Villa Ginestra domina la costiera con una delle viste più fotografate del Mediterraneo: dalle terrazze a picco lo sguardo corre da Li Galli a Praiano.

Interni in maiolica vietrese e lino bianco, quattro camere con vista e una cucina professionale dove lo chef di casa prepara la colazione ogni mattina.

Il limoneto a terrazze e la piscina scavata nella roccia completano un rifugio raggiungibile in tre minuti a piedi dalla Spiaggia Grande, con servizio navetta interno per i bagagli.',
    'amenities' => 
    array (
      0 => 'Terrazze vista mare',
      1 => 'Piscina nella roccia',
      2 => 'Limoneto privato',
      3 => 'Maioliche d\'epoca',
      4 => 'Aria condizionata',
      5 => 'Wi-Fi ad alta velocità',
      6 => 'Navetta interna',
      7 => 'Cucina professionale',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Colazione preparata dallo chef',
      1 => 'Governante giornaliera',
      2 => 'Navetta per la spiaggia',
      3 => 'Concierge dedicato',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Pranzi e cene dello chef (su richiesta)',
      1 => 'Transfer da/per Napoli',
      2 => 'Escursioni in barca',
    ),
    'supplier' => 'Riviera Dimore',
    'map' => 
    array (
      'x' => 30,
      'y' => 34,
    ),
    'lnglat' => 
    array (
      0 => 14.488,
      1 => 40.63,
    ),
    'badge' => 'VISTA MARE',
    'bedrooms' => 4,
    'beds' => 5,
    'bathrooms' => 4,
    'maxGuests' => 8,
    'sqm' => 320,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Chef di casa',
      1 => 'Governante',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-ginestra-positano/1.webp',
      1 => '/images/products/villa-ginestra-positano/2.webp',
      2 => '/images/products/villa-ginestra-positano/3.webp',
      3 => '/images/products/villa-ginestra-positano/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '4 camere · 4 bagni · 320 m²',
    'location' => 'Positano, Costiera Amalfitana',
    'price' => 4200,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  4 => 
  array (
    'id' => 'vl-05',
    'slug' => 'palazzo-ravello',
    'title' => 'Palazzo Ravello',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Ravello',
    'pricePerDay' => 6000,
    'depositAmount' => 9000,
    'rating' => 4.8,
    'reviewsCount' => 19,
    'description' => 'Un palazzo nobiliare del Settecento nel cuore di Ravello, a pochi passi da Villa Rufolo: soffitti affrescati, chiostro privato e un giardino pensile che guarda il mare da trecento metri d\'altezza.

Le sei suite conservano pavimenti originali in cotto napoletano e sono servite da un maggiordomo che cura ogni dettaglio, dal risveglio alla mise en place delle cene in giardino.

Nelle sere d\'estate il belvedere privato diventa il palco ideale per concerti da camera e cene di gala fino a trenta ospiti.',
    'amenities' => 
    array (
      0 => 'Giardino pensile sul mare',
      1 => 'Affreschi originali',
      2 => 'Chiostro privato',
      3 => 'Piscina panoramica',
      4 => 'Ascensore',
      5 => 'Wi-Fi',
      6 => 'Sala musica con pianoforte',
      7 => 'Spazi per eventi',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante giornaliera',
      2 => 'Concierge dedicato',
      3 => 'Utenze',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Organizzazione eventi',
      2 => 'Transfer',
    ),
    'supplier' => 'Riviera Dimore',
    'map' => 
    array (
      'x' => 66,
      'y' => 26,
    ),
    'lnglat' => 
    array (
      0 => 14.612,
      1 => 40.649,
    ),
    'badge' => 'DIMORA STORICA',
    'bedrooms' => 6,
    'beds' => 7,
    'bathrooms' => 6,
    'maxGuests' => 12,
    'sqm' => 540,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante',
      2 => 'Giardiniere',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/palazzo-ravello/1.webp',
      1 => '/images/products/palazzo-ravello/2.webp',
      2 => '/images/products/palazzo-ravello/3.webp',
      3 => '/images/products/palazzo-ravello/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '6 camere · 6 bagni · 540 m²',
    'location' => 'Ravello, Costiera Amalfitana',
    'price' => 6000,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  5 => 
  array (
    'id' => 'vl-06',
    'slug' => 'villa-lariana-bellagio',
    'title' => 'Villa Lariana',
    'destinationId' => 'lago-di-como',
    'locality' => 'Bellagio',
    'pricePerDay' => 7000,
    'depositAmount' => 10000,
    'rating' => 4.9,
    'reviewsCount' => 22,
    'description' => 'Sulla punta di Bellagio, Villa Lariana è una dimora liberty del 1911 con parco secolare e darsena privata direttamente sul lago: l\'indirizzo che ha reso celebre il ramo di Como.

Saloni con stucchi originali, sei suite affacciate sull\'acqua e una veranda in ferro battuto dove viene servito l\'aperitivo al tramonto, quando il lago si accende di rosa.

La darsena ospita il motoscafo della casa: il maggiordomo organizza traversate private verso Villa d\'Este e i ristoranti stellati della sponda occidentale.',
    'amenities' => 
    array (
      0 => 'Darsena privata',
      1 => 'Parco secolare',
      2 => 'Motoscafo della casa',
      3 => 'Piscina riscaldata',
      4 => 'Stucchi liberty originali',
      5 => 'Wi-Fi ad alta velocità',
      6 => 'Sala biliardo',
      7 => 'Cantina vini',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante giornaliera',
      2 => 'Skipper del motoscafo',
      3 => 'Concierge dedicato',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Carburante del motoscafo',
      2 => 'Transfer da/per Milano',
    ),
    'supplier' => 'Lario Collection',
    'map' => 
    array (
      'x' => 55,
      'y' => 34,
    ),
    'lnglat' => 
    array (
      0 => 9.26,
      1 => 45.988,
    ),
    'badge' => 'DARSENA PRIVATA',
    'bedrooms' => 6,
    'beds' => 8,
    'bathrooms' => 7,
    'maxGuests' => 12,
    'sqm' => 600,
    'pool' => true,
    'seaView' => false,
    'staff' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante',
      2 => 'Skipper',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-lariana-bellagio/1.webp',
      1 => '/images/products/villa-lariana-bellagio/2.webp',
      2 => '/images/products/villa-lariana-bellagio/3.webp',
      3 => '/images/products/villa-lariana-bellagio/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '6 camere · 7 bagni · 600 m²',
    'location' => 'Bellagio, Lago di Como',
    'price' => 7000,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  6 => 
  array (
    'id' => 'vl-07',
    'slug' => 'villa-cernobbio',
    'title' => 'Villa Cernobbio',
    'destinationId' => 'lago-di-como',
    'locality' => 'Cernobbio',
    'pricePerDay' => 3500,
    'depositAmount' => 5000,
    'rating' => 4.7,
    'reviewsCount' => 36,
    'description' => 'A due passi da Villa d\'Este, una residenza ottocentesca ristrutturata con eleganza contemporanea: quattro camere, giardino all\'italiana e vista aperta sul primo bacino del lago.

Gli interni firmati da un atelier comasco mescolano sete locali, marmi e pezzi di design; la cucina si apre su una terrazza coperta per colazioni sull\'acqua.

Posizione perfetta per vivere il lago: imbarcadero a 200 metri, centro di Cernobbio a cinque minuti a piedi e Como raggiungibile in dieci minuti d\'auto.',
    'amenities' => 
    array (
      0 => 'Giardino all\'italiana',
      1 => 'Vista lago',
      2 => 'Terrazza coperta',
      3 => 'Aria condizionata',
      4 => 'Wi-Fi ad alta velocità',
      5 => 'Parcheggio privato',
      6 => 'Sauna',
      7 => 'Biciclette a disposizione',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Pulizie giornaliere',
      1 => 'Concierge su chiamata',
      2 => 'Utenze',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Motoscafo con skipper (su richiesta)',
      2 => 'Transfer',
    ),
    'supplier' => 'Lario Collection',
    'map' => 
    array (
      'x' => 24,
      'y' => 66,
    ),
    'lnglat' => 
    array (
      0 => 9.073,
      1 => 45.843,
    ),
    'bedrooms' => 4,
    'beds' => 5,
    'bathrooms' => 4,
    'maxGuests' => 8,
    'sqm' => 300,
    'pool' => false,
    'seaView' => false,
    'staff' => 
    array (
      0 => 'Governante',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-cernobbio/1.webp',
      1 => '/images/products/villa-cernobbio/2.webp',
      2 => '/images/products/villa-cernobbio/3.webp',
      3 => '/images/products/villa-cernobbio/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '4 camere · 4 bagni · 300 m²',
    'location' => 'Cernobbio, Lago di Como',
    'price' => 3500,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'Dimora Storica',
  ),
  7 => 
  array (
    'id' => 'vl-08',
    'slug' => 'villa-marinella-porto-rotondo',
    'title' => 'Villa Marinella',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Rotondo',
    'pricePerDay' => 4800,
    'depositAmount' => 7000,
    'rating' => 4.8,
    'reviewsCount' => 24,
    'description' => 'Adagiata sulla collina che abbraccia il porticciolo di Porto Rotondo, Villa Marinella guarda il golfo di Marinella e l\'Isola di Tavolara che chiude l\'orizzonte a sud. Cinque camere luminose, tutte con accesso diretto al giardino mediterraneo.

Il living si apre senza soluzione di continuità sulla piscina a sfioro e su una terrazza in teak per le cene all\'aperto, mentre un pergolato di gelsomino ripara l\'angolo lounge dal sole del pomeriggio.

A pochi minuti a piedi dalla Piazzetta San Marco e dal molo, è la base perfetta per vivere Porto Rotondo tra aperitivi, boutique e uscite in barca.',
    'amenities' => 
    array (
      0 => 'Piscina a sfioro',
      1 => 'Vista sul golfo di Marinella',
      2 => 'Giardino mediterraneo',
      3 => 'Aria condizionata',
      4 => 'Wi-Fi ad alta velocità',
      5 => 'Barbecue e cucina esterna',
      6 => 'Parcheggio per 3 auto',
      7 => 'Sistema audio multiroom',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Governante giornaliera',
      1 => 'Concierge dedicato 7/7',
      2 => 'Cambio biancheria a giorni alterni',
      3 => 'Utenze e climatizzazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Transfer da/per aeroporto Olbia',
      2 => 'Servizio spiaggia',
    ),
    'supplier' => 'Smeralda Estates',
    'map' => 
    array (
      'x' => 72,
      'y' => 60,
    ),
    'lnglat' => 
    array (
      0 => 9.546,
      1 => 41.031,
    ),
    'badge' => 'VISTA MARE',
    'bedrooms' => 5,
    'beds' => 6,
    'bathrooms' => 5,
    'maxGuests' => 10,
    'sqm' => 420,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Governante',
      1 => 'Giardiniere',
      2 => 'Concierge',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-marinella-porto-rotondo/1.webp',
      1 => '/images/products/villa-marinella-porto-rotondo/2.webp',
      2 => '/images/products/villa-marinella-porto-rotondo/3.webp',
      3 => '/images/products/villa-marinella-porto-rotondo/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '5 camere · 5 bagni · 420 m²',
    'location' => 'Porto Rotondo, Costa Smeralda',
    'price' => 4800,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  8 => 
  array (
    'id' => 'vl-09',
    'slug' => 'villa-tramonto-praiano',
    'title' => 'Villa Tramonto',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Praiano',
    'pricePerDay' => 3800,
    'depositAmount' => 5500,
    'rating' => 4.9,
    'reviewsCount' => 28,
    'description' => 'Praiano è il balcone da cui la Costiera guarda il sole calare dietro Positano, e Villa Tramonto ne occupa il punto più scenografico: una terrazza a strapiombo dove ogni sera il mare si tinge d\'oro e di rosa.

Quattro camere in maiolica e calce bianca, una piscina a sfioro sospesa sul blu e un pergolato di bouganville sotto cui lo chef di casa serve la colazione con vista su Capri.

Una scalinata privata scende verso la caletta di Marina di Praia, mentre il centro del paese, con la sua chiesa maiolicata, è a pochi minuti a piedi.',
    'amenities' => 
    array (
      0 => 'Terrazza vista tramonto',
      1 => 'Piscina a sfioro sul mare',
      2 => 'Pergolato di bouganville',
      3 => 'Maioliche vietresi',
      4 => 'Aria condizionata',
      5 => 'Wi-Fi ad alta velocità',
      6 => 'Accesso privato alla caletta',
      7 => 'Cucina professionale',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Colazione preparata dallo chef',
      1 => 'Governante giornaliera',
      2 => 'Concierge dedicato',
      3 => 'Utenze e climatizzazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Pranzi e cene dello chef (su richiesta)',
      1 => 'Transfer da/per Napoli',
      2 => 'Escursioni in barca',
    ),
    'supplier' => 'Riviera Dimore',
    'map' => 
    array (
      'x' => 40,
      'y' => 42,
    ),
    'lnglat' => 
    array (
      0 => 14.524,
      1 => 40.61,
    ),
    'badge' => 'VISTA MARE',
    'bedrooms' => 4,
    'beds' => 5,
    'bathrooms' => 4,
    'maxGuests' => 8,
    'sqm' => 300,
    'pool' => true,
    'seaView' => true,
    'staff' => 
    array (
      0 => 'Chef di casa',
      1 => 'Governante',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-tramonto-praiano/1.webp',
      1 => '/images/products/villa-tramonto-praiano/2.webp',
      2 => '/images/products/villa-tramonto-praiano/3.webp',
      3 => '/images/products/villa-tramonto-praiano/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '4 camere · 4 bagni · 300 m²',
    'location' => 'Praiano, Costiera Amalfitana',
    'price' => 3800,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  9 => 
  array (
    'id' => 'vl-10',
    'slug' => 'villa-varenna',
    'title' => 'Villa Varenna',
    'destinationId' => 'lago-di-como',
    'locality' => 'Varenna',
    'pricePerDay' => 4500,
    'depositAmount' => 6500,
    'rating' => 4.8,
    'reviewsCount' => 20,
    'description' => 'Sulla sponda orientale del lago, dove Varenna custodisce l\'atmosfera più autentica del Lario, questa villa ottocentesca digrada verso l\'acqua con un giardino a terrazze di ulivi, cipressi e limoni in vaso.

Cinque camere affacciate sul ramo di Lecco, saloni con pavimenti in seminato veneziano e una veranda a picco sul lago dove viene servito l\'aperitivo mentre i battelli attraversano verso Bellagio.

La darsena privata ospita il motoscafo della casa: dal pontile si raggiungono in pochi minuti Villa Monastero e i ristoranti della sponda occidentale.',
    'amenities' => 
    array (
      0 => 'Darsena e pontile privati',
      1 => 'Giardino a terrazze sul lago',
      2 => 'Veranda panoramica',
      3 => 'Piscina riscaldata',
      4 => 'Aria condizionata',
      5 => 'Wi-Fi ad alta velocità',
      6 => 'Camino nel salone',
      7 => 'Parcheggio privato',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante giornaliera',
      2 => 'Concierge dedicato',
      3 => 'Utenze e climatizzazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Chef privato (su richiesta)',
      1 => 'Motoscafo con skipper (su richiesta)',
      2 => 'Transfer da/per Milano',
    ),
    'supplier' => 'Lario Collection',
    'map' => 
    array (
      'x' => 66,
      'y' => 30,
    ),
    'lnglat' => 
    array (
      0 => 9.284,
      1 => 46.011,
    ),
    'badge' => 'DARSENA PRIVATA',
    'bedrooms' => 5,
    'beds' => 6,
    'bathrooms' => 5,
    'maxGuests' => 10,
    'sqm' => 400,
    'pool' => true,
    'seaView' => false,
    'staff' => 
    array (
      0 => 'Maggiordomo',
      1 => 'Governante',
      2 => 'Giardiniere',
    ),
    'chefAvailable' => true,
    'category' => 'villa',
    'images' => 
    array (
      0 => '/images/products/villa-varenna/1.webp',
      1 => '/images/products/villa-varenna/2.webp',
      2 => '/images/products/villa-varenna/3.webp',
      3 => '/images/products/villa-varenna/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '5 camere · 5 bagni · 400 m²',
    'location' => 'Varenna, Lago di Como',
    'price' => 4500,
    'unit' => 'notte',
    'capacity' => 8,
    'capacityLabel' => '8 ospiti',
    'featured' => true,
    'subtype' => 'Villa con Piscina',
  ),
  10 => 
  array (
    'id' => 'au-01',
    'slug' => 'ferrari-sf90-stradale',
    'title' => 'Ferrari SF90 Stradale',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 1600,
    'depositAmount' => 10000,
    'rating' => 4.9,
    'reviewsCount' => 18,
    'description' => 'L\'ibrida più potente mai prodotta a Maranello: 1.000 cavalli, trazione integrale e uno scatto 0-100 in 2,5 secondi. La SF90 Stradale trasforma la litoranea della Gallura in un\'esperienza da pista.

Consegnata a domicilio in tutta la Costa Smeralda con serbatoio pieno, briefing tecnico personale e assistenza dedicata 24/7 per l\'intera durata del noleggio.',
    'amenities' => 
    array (
      0 => '1.000 CV ibridi',
      1 => 'Modalità eDrive silenziosa',
      2 => 'Apple CarPlay',
      3 => 'Telecamere 360°',
      4 => 'Sollevatore anteriore',
      5 => 'Interni in pelle Poltrona Frau',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna e ritiro a domicilio',
      1 => 'Assicurazione Kasko con franchigia',
      2 => 'Assistenza stradale dedicata 24/7',
      3 => '100 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€4/km)',
      2 => 'Autista (su richiesta)',
    ),
    'supplier' => 'Smeralda Motors',
    'map' => 
    array (
      'x' => 55,
      'y' => 38,
    ),
    'lnglat' => 
    array (
      0 => 9.552,
      1 => 41.13,
    ),
    'badge' => 'CONSEGNA INCLUSA',
    'make' => 'Ferrari',
    'model' => 'SF90 Stradale',
    'year' => 2024,
    'seats' => 2,
    'transmission' => 'Automatico',
    'fuelType' => 'Ibrido plug-in',
    'horsepower' => 1000,
    'kmIncluded' => 100,
    'deliveryIncluded' => true,
    'autoType' => 'Supercar',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/ferrari-sf90-stradale/1.webp',
      1 => '/images/products/ferrari-sf90-stradale/2.webp',
      2 => '/images/products/ferrari-sf90-stradale/3.webp',
      3 => '/images/products/ferrari-sf90-stradale/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '650 CV · Automatico · 2 posti',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 1600,
    'unit' => 'giorno',
    'capacity' => 2,
    'capacityLabel' => '2 posti',
    'featured' => true,
    'subtype' => 'Supercar & Spider',
  ),
  11 => 
  array (
    'id' => 'au-02',
    'slug' => 'lamborghini-huracan-evo-spyder',
    'title' => 'Lamborghini Huracán Evo Spyder',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 1400,
    'depositAmount' => 9000,
    'rating' => 4.8,
    'reviewsCount' => 25,
    'description' => 'Il V10 aspirato più iconico di Sant\'Agata, a cielo aperto: 640 cavalli e un sound che riecheggia tra le curve del Pevero. La Spyder è l\'auto simbolo delle estati in Costa Smeralda.

Capote elettrica in 17 secondi, quattro ruote sterzanti e modalità di guida ANIMA per adattare l\'auto dal lungomare alla statale di montagna.',
    'amenities' => 
    array (
      0 => 'V10 aspirato 640 CV',
      1 => 'Capote elettrica',
      2 => 'Quattro ruote sterzanti',
      3 => 'Apple CarPlay',
      4 => 'Freni carboceramici',
      5 => 'Interni in Alcantara',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna e ritiro a domicilio',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '100 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€4/km)',
      2 => 'Pulizia straordinaria',
    ),
    'supplier' => 'Smeralda Motors',
    'map' => 
    array (
      'x' => 60,
      'y' => 50,
    ),
    'lnglat' => 
    array (
      0 => 9.531,
      1 => 41.141,
    ),
    'badge' => 'CABRIO',
    'make' => 'Lamborghini',
    'model' => 'Huracán Evo Spyder',
    'year' => 2023,
    'seats' => 2,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 640,
    'kmIncluded' => 100,
    'deliveryIncluded' => true,
    'autoType' => 'Supercar',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/lamborghini-huracan-evo-spyder/1.webp',
      1 => '/images/products/lamborghini-huracan-evo-spyder/2.webp',
      2 => '/images/products/lamborghini-huracan-evo-spyder/3.webp',
      3 => '/images/products/lamborghini-huracan-evo-spyder/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '650 CV · Automatico · 2 posti',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 1400,
    'unit' => 'giorno',
    'capacity' => 2,
    'capacityLabel' => '2 posti',
    'featured' => true,
    'subtype' => 'Supercar & Spider',
  ),
  12 => 
  array (
    'id' => 'au-03',
    'slug' => 'range-rover-autobiography',
    'title' => 'Range Rover Autobiography',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Olbia',
    'pricePerDay' => 900,
    'depositAmount' => 5000,
    'rating' => 4.7,
    'reviewsCount' => 40,
    'description' => 'L\'ammiraglia britannica nella configurazione più raffinata: sedili executive posteriori, frigo bar e insonorizzazione totale. Perfetta per famiglie ed equipaggi che si muovono tra ville, porto e aeroporto.

Disponibile anche con autista NCC convenzionato per trasferimenti ed escursioni sull\'isola.',
    'amenities' => 
    array (
      0 => 'Sedili executive posteriori',
      1 => 'Frigo bar',
      2 => 'Tetto panoramico',
      3 => 'Sound system Meridian',
      4 => 'Sospensioni pneumatiche',
      5 => '7 posti disponibili',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna in aeroporto Olbia',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '200 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Autista (su richiesta)',
      2 => 'Seggiolini bimbi',
    ),
    'supplier' => 'Gallura Prestige',
    'map' => 
    array (
      'x' => 72,
      'y' => 58,
    ),
    'lnglat' => 
    array (
      0 => 9.517,
      1 => 40.925,
    ),
    'make' => 'Land Rover',
    'model' => 'Range Rover Autobiography',
    'year' => 2024,
    'seats' => 5,
    'transmission' => 'Automatico',
    'fuelType' => 'Diesel mild-hybrid',
    'horsepower' => 350,
    'kmIncluded' => 200,
    'deliveryIncluded' => true,
    'autoType' => 'SUV',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/range-rover-autobiography/1.webp',
      1 => '/images/products/range-rover-autobiography/2.webp',
      2 => '/images/products/range-rover-autobiography/3.webp',
      3 => '/images/products/range-rover-autobiography/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '650 CV · Automatico · 5 posti',
    'location' => 'Olbia, Costa Smeralda',
    'price' => 900,
    'unit' => 'giorno',
    'capacity' => 5,
    'capacityLabel' => '5 posti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'SUV & Gran Turismo',
  ),
  13 => 
  array (
    'id' => 'au-04',
    'slug' => 'porsche-911-turbo-cabrio',
    'title' => 'Porsche 911 Turbo Cabriolet',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Positano',
    'pricePerDay' => 1100,
    'depositAmount' => 7000,
    'rating' => 4.8,
    'reviewsCount' => 31,
    'description' => 'La 911 Turbo scoperta è la compagna ideale della statale amalfitana: compatta quanto basta per i tornanti, potente da riempire i rettilinei sul mare con i suoi 580 cavalli.

Consegna a Positano o Sorrento con briefing sul percorso: i nostri driver conoscono ogni tornante della costiera e consigliano orari e soste fotografiche.',
    'amenities' => 
    array (
      0 => '580 CV biturbo',
      1 => 'Capote in 12 secondi',
      2 => 'Trazione integrale',
      3 => 'Apple CarPlay',
      4 => 'Cerchi 21 pollici',
      5 => 'Cronografo Sport Chrono',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna e ritiro in costiera',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '150 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€3/km)',
      2 => 'Parcheggi e ZTL',
    ),
    'supplier' => 'Riviera Motors Club',
    'map' => 
    array (
      'x' => 26,
      'y' => 42,
    ),
    'lnglat' => 
    array (
      0 => 14.48,
      1 => 40.627,
    ),
    'badge' => 'CABRIO',
    'make' => 'Porsche',
    'model' => '911 Turbo Cabriolet',
    'year' => 2023,
    'seats' => 4,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 580,
    'kmIncluded' => 150,
    'deliveryIncluded' => true,
    'autoType' => 'Cabrio',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/porsche-911-turbo-cabrio/1.webp',
      1 => '/images/products/porsche-911-turbo-cabrio/2.webp',
      2 => '/images/products/porsche-911-turbo-cabrio/3.webp',
      3 => '/images/products/porsche-911-turbo-cabrio/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '650 CV · Automatico · 4 posti',
    'location' => 'Positano, Costiera Amalfitana',
    'price' => 1100,
    'unit' => 'giorno',
    'capacity' => 4,
    'capacityLabel' => '4 posti',
    'featured' => true,
    'subtype' => 'SUV & Gran Turismo',
  ),
  14 => 
  array (
    'id' => 'au-05',
    'slug' => 'maserati-grancabrio-trofeo',
    'title' => 'Maserati GranCabrio Trofeo',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Amalfi',
    'pricePerDay' => 950,
    'depositAmount' => 6000,
    'rating' => 4.6,
    'reviewsCount' => 22,
    'description' => 'Quattro posti veri a cielo aperto e il V6 Nettuno derivato dalla MC20: la GranCabrio è il gran turismo italiano per eccellenza, disegnato per la dolce vita della costiera.

Bagagliaio capiente per i weekend lunghi e interni in pelle pieno fiore lavorata a Modena.',
    'amenities' => 
    array (
      0 => 'V6 Nettuno 550 CV',
      1 => '4 posti comodi',
      2 => 'Capote in tessuto',
      3 => 'Sound system Sonus faber',
      4 => 'Apple CarPlay',
      5 => 'Sedili ventilati',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna e ritiro in costiera',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '150 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€3/km)',
      2 => 'Autista (su richiesta)',
    ),
    'supplier' => 'Riviera Motors Club',
    'map' => 
    array (
      'x' => 60,
      'y' => 38,
    ),
    'lnglat' => 
    array (
      0 => 14.601,
      1 => 40.634,
    ),
    'badge' => 'CABRIO',
    'make' => 'Maserati',
    'model' => 'GranCabrio Trofeo',
    'year' => 2024,
    'seats' => 4,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 550,
    'kmIncluded' => 150,
    'deliveryIncluded' => true,
    'autoType' => 'Cabrio',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/maserati-grancabrio-trofeo/1.webp',
      1 => '/images/products/maserati-grancabrio-trofeo/2.webp',
      2 => '/images/products/maserati-grancabrio-trofeo/3.webp',
      3 => '/images/products/maserati-grancabrio-trofeo/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '650 CV · Automatico · 4 posti',
    'location' => 'Amalfi, Costiera Amalfitana',
    'price' => 950,
    'unit' => 'giorno',
    'capacity' => 4,
    'capacityLabel' => '4 posti',
    'featured' => true,
    'subtype' => 'SUV & Gran Turismo',
  ),
  15 => 
  array (
    'id' => 'au-06',
    'slug' => 'bentley-continental-gtc',
    'title' => 'Bentley Continental GTC',
    'destinationId' => 'lago-di-como',
    'locality' => 'Como',
    'pricePerDay' => 1300,
    'depositAmount' => 8000,
    'rating' => 4.9,
    'reviewsCount' => 16,
    'description' => 'Il gran turismo di Crewe a cielo aperto: W12 da 659 cavalli, capote in tweed e una presenza scenica perfetta per gli arrivi a Villa d\'Este e alle serate sul lago.

Interni in pelle e radica lavorati a mano, comfort assoluto anche nei lunghi trasferimenti da Milano.',
    'amenities' => 
    array (
      0 => 'W12 659 CV',
      1 => 'Capote in tweed',
      2 => 'Interni in radica',
      3 => 'Massaggio sedili',
      4 => 'Sound system Naim',
      5 => 'Trazione integrale',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna sul lago o a Milano',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '200 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€3/km)',
      2 => 'Autista (su richiesta)',
    ),
    'supplier' => 'Lario Prestige Cars',
    'map' => 
    array (
      'x' => 28,
      'y' => 78,
    ),
    'lnglat' => 
    array (
      0 => 9.083,
      1 => 45.812,
    ),
    'make' => 'Bentley',
    'model' => 'Continental GTC',
    'year' => 2023,
    'seats' => 4,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 659,
    'kmIncluded' => 200,
    'deliveryIncluded' => true,
    'autoType' => 'Cabrio',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/bentley-continental-gtc/1.webp',
      1 => '/images/products/bentley-continental-gtc/2.webp',
      2 => '/images/products/bentley-continental-gtc/3.webp',
      3 => '/images/products/bentley-continental-gtc/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '650 CV · Automatico · 4 posti',
    'location' => 'Como, Lago di Como',
    'price' => 1300,
    'unit' => 'giorno',
    'capacity' => 4,
    'capacityLabel' => '4 posti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'SUV & Gran Turismo',
  ),
  16 => 
  array (
    'id' => 'au-07',
    'slug' => 'mercedes-classe-g-63',
    'title' => 'Mercedes-AMG G 63',
    'destinationId' => 'lago-di-como',
    'locality' => 'Menaggio',
    'pricePerDay' => 1100,
    'depositAmount' => 7000,
    'rating' => 4.7,
    'reviewsCount' => 28,
    'description' => 'L\'icona off-road reinterpretata da AMG: 585 cavalli, assetto rialzato e una presenza inconfondibile tra i tornanti che salgono dal lago verso la Val d\'Intelvi.

Spazio per cinque adulti e bagagli, perfetta per raggiungere ville e rifugi mantenendo lo stile di un\'ammiraglia.',
    'amenities' => 
    array (
      0 => 'V8 biturbo 585 CV',
      1 => 'Interni Nappa',
      2 => 'Tetto apribile',
      3 => 'Telecamere 360°',
      4 => 'Burmester sound',
      5 => 'Pacchetto Night',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna sul lago',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '200 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€3/km)',
      2 => 'Seggiolini bimbi',
    ),
    'supplier' => 'Lario Prestige Cars',
    'map' => 
    array (
      'x' => 36,
      'y' => 30,
    ),
    'lnglat' => 
    array (
      0 => 9.237,
      1 => 46.022,
    ),
    'make' => 'Mercedes-AMG',
    'model' => 'G 63',
    'year' => 2024,
    'seats' => 5,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 585,
    'kmIncluded' => 200,
    'deliveryIncluded' => true,
    'autoType' => 'SUV',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/mercedes-classe-g-63/1.webp',
      1 => '/images/products/mercedes-classe-g-63/2.webp',
      2 => '/images/products/mercedes-classe-g-63/3.webp',
      3 => '/images/products/mercedes-classe-g-63/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '650 CV · Automatico · 5 posti',
    'location' => 'Menaggio, Lago di Como',
    'price' => 1100,
    'unit' => 'giorno',
    'capacity' => 5,
    'capacityLabel' => '5 posti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'SUV & Gran Turismo',
  ),
  17 => 
  array (
    'id' => 'au-08',
    'slug' => 'rolls-royce-cullinan',
    'title' => 'Rolls-Royce Cullinan',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Rotondo',
    'pricePerDay' => 1500,
    'depositAmount' => 12000,
    'rating' => 4.8,
    'reviewsCount' => 14,
    'description' => 'L\'ammiraglia di Goodwood nella sua veste più imponente: il Cullinan unisce la presenza di un fuoristrada di lusso al silenzio ovattato di una lounge viaggiante, con i celebri sedili posteriori a poltrona e il cielo stellato sul padiglione.

Ideale per arrivi di scena e trasferimenti tra ville, porto e aeroporto, è disponibile con autista NCC convenzionato per vivere la Costa Smeralda dal sedile posteriore.',
    'amenities' => 
    array (
      0 => 'V12 biturbo 571 CV',
      1 => 'Cielo stellato Starlight',
      2 => 'Sedili posteriori a poltrona',
      3 => 'Frigo bar e tavolini',
      4 => 'Sospensioni Magic Carpet Ride',
      5 => 'Sound system su misura',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna in aeroporto Olbia',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '200 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Autista NCC (su richiesta)',
      2 => 'Km extra (€5/km)',
    ),
    'supplier' => 'Gallura Prestige',
    'map' => 
    array (
      'x' => 74,
      'y' => 66,
    ),
    'lnglat' => 
    array (
      0 => 9.5455,
      1 => 41.0285,
    ),
    'badge' => 'AUTISTA DISPONIBILE',
    'make' => 'Rolls-Royce',
    'model' => 'Cullinan',
    'year' => 2024,
    'seats' => 5,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 571,
    'kmIncluded' => 200,
    'deliveryIncluded' => true,
    'autoType' => 'SUV',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/rolls-royce-cullinan/1.webp',
      1 => '/images/products/rolls-royce-cullinan/2.webp',
      2 => '/images/products/rolls-royce-cullinan/3.webp',
      3 => '/images/products/rolls-royce-cullinan/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '650 CV · Automatico · 5 posti',
    'location' => 'Porto Rotondo, Costa Smeralda',
    'price' => 1500,
    'unit' => 'giorno',
    'capacity' => 5,
    'capacityLabel' => '5 posti',
    'featured' => true,
    'subtype' => 'SUV & Gran Turismo',
  ),
  18 => 
  array (
    'id' => 'au-09',
    'slug' => 'aston-martin-db12-volante',
    'title' => 'Aston Martin DB12 Volante',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Praiano',
    'pricePerDay' => 1250,
    'depositAmount' => 8000,
    'rating' => 4.8,
    'reviewsCount' => 12,
    'description' => 'Il primo "Super Tourer" di Gaydon a cielo aperto: 680 cavalli, una linea scultorea e una capote in tessuto che si apre in quattordici secondi per godere appieno dei profumi e del sole della costiera.

Consegna a Praiano o Positano con briefing sul percorso: i tornanti tra Amalfi e Positano diventano il palcoscenico naturale di una delle GT più belle di sempre.',
    'amenities' => 
    array (
      0 => 'V8 biturbo 680 CV',
      1 => 'Capote in tessuto',
      2 => 'Interni in pelle pieno fiore',
      3 => 'Apple CarPlay wireless',
      4 => 'Sound system Bowers & Wilkins',
      5 => 'Modalità di guida adattive',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna e ritiro in costiera',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '150 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€3/km)',
      2 => 'Parcheggi e ZTL',
    ),
    'supplier' => 'Riviera Motors Club',
    'map' => 
    array (
      'x' => 44,
      'y' => 40,
    ),
    'lnglat' => 
    array (
      0 => 14.5255,
      1 => 40.6108,
    ),
    'badge' => 'CABRIO',
    'make' => 'Aston Martin',
    'model' => 'DB12 Volante',
    'year' => 2024,
    'seats' => 4,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 680,
    'kmIncluded' => 150,
    'deliveryIncluded' => true,
    'autoType' => 'Cabrio',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/aston-martin-db12-volante/1.webp',
      1 => '/images/products/aston-martin-db12-volante/2.webp',
      2 => '/images/products/aston-martin-db12-volante/3.webp',
      3 => '/images/products/aston-martin-db12-volante/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '650 CV · Automatico · 4 posti',
    'location' => 'Praiano, Costiera Amalfitana',
    'price' => 1250,
    'unit' => 'giorno',
    'capacity' => 4,
    'capacityLabel' => '4 posti',
    'featured' => true,
    'subtype' => 'SUV & Gran Turismo',
  ),
  19 => 
  array (
    'id' => 'au-10',
    'slug' => 'ferrari-roma-spider',
    'title' => 'Ferrari Roma Spider',
    'destinationId' => 'lago-di-como',
    'locality' => 'Bellagio',
    'pricePerDay' => 1350,
    'depositAmount' => 9000,
    'rating' => 4.9,
    'reviewsCount' => 11,
    'description' => 'La "nuova dolce vita" secondo Maranello, ora scoperta: la Roma Spider veste il V8 biturbo da 620 cavalli con una capote in tessuto morbida che ne preserva le proporzioni da gran turismo elegante.

L\'auto perfetta per i tornanti che salgono da Bellagio verso le ville del Triangolo Lariano, consegnata sul lago o a Milano con il serbatoio pieno.',
    'amenities' => 
    array (
      0 => 'V8 biturbo 620 CV',
      1 => 'Capote in tessuto',
      2 => '2+2 posti',
      3 => 'Apple CarPlay',
      4 => 'Sedili in pelle Frau',
      5 => 'Manettino cinque posizioni',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Consegna sul lago o a Milano',
      1 => 'Assicurazione Kasko con franchigia',
      2 => '200 km al giorno inclusi',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante',
      1 => 'Km extra (€4/km)',
      2 => 'Autista (su richiesta)',
    ),
    'supplier' => 'Lario Prestige Cars',
    'map' => 
    array (
      'x' => 52,
      'y' => 40,
    ),
    'lnglat' => 
    array (
      0 => 9.261,
      1 => 45.986,
    ),
    'badge' => 'CABRIO',
    'make' => 'Ferrari',
    'model' => 'Roma Spider',
    'year' => 2024,
    'seats' => 4,
    'transmission' => 'Automatico',
    'fuelType' => 'Benzina',
    'horsepower' => 620,
    'kmIncluded' => 200,
    'deliveryIncluded' => true,
    'autoType' => 'Cabrio',
    'category' => 'auto',
    'images' => 
    array (
      0 => '/images/products/ferrari-roma-spider/1.webp',
      1 => '/images/products/ferrari-roma-spider/2.webp',
      2 => '/images/products/ferrari-roma-spider/3.webp',
      3 => '/images/products/ferrari-roma-spider/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '650 CV · Automatico · 4 posti',
    'location' => 'Bellagio, Lago di Como',
    'price' => 1350,
    'unit' => 'giorno',
    'capacity' => 4,
    'capacityLabel' => '4 posti',
    'featured' => true,
    'subtype' => 'SUV & Gran Turismo',
  ),
  20 => 
  array (
    'id' => 'yc-01',
    'slug' => 'ferretti-680',
    'title' => 'Ferretti 680',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 4000,
    'depositAmount' => 6000,
    'rating' => 4.9,
    'reviewsCount' => 21,
    'description' => 'Ventuno metri di eleganza italiana: il Ferretti 680 offre un flybridge generoso, quattro cabine per gli ospiti e una plancetta idraulica per bagni al largo dell\'arcipelago di La Maddalena.

L\'equipaggio di due persone cura rotta, aperitivi a bordo e prenotazioni nei ristoranti raggiungibili solo dal mare. La giornata tipo: Spargi al mattino, pranzo alla fonda a Budelli, rientro con aperitivo al tramonto.',
    'amenities' => 
    array (
      0 => 'Flybridge con prendisole',
      1 => '4 cabine ospiti',
      2 => 'Plancetta idraulica',
      3 => 'Tender con motore 40 CV',
      4 => 'Attrezzatura snorkeling',
      5 => 'Impianto audio esterno',
      6 => 'Aria condizionata',
      7 => 'Cucina completa',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper e marinaio',
      1 => 'Aperitivo di benvenuto',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante (APA 25%)',
      1 => 'Ormeggi fuori porto base',
      2 => 'Chef di bordo (su richiesta)',
    ),
    'supplier' => 'Smeralda Charter',
    'map' => 
    array (
      'x' => 58,
      'y' => 44,
    ),
    'lnglat' => 
    array (
      0 => 9.5365,
      1 => 41.1355,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Ferretti',
    'lengthMeters' => 21,
    'cabins' => 4,
    'bathrooms' => 3,
    'maxGuests' => 11,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 2,
    'cruiseSpeedKnots' => 27,
    'fuelIncluded' => false,
    'marina' => 'Marina di Porto Cervo',
    'yachtType' => 'Motor yacht',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/ferretti-680/1.webp',
      1 => '/images/products/ferretti-680/2.webp',
      2 => '/images/products/ferretti-680/3.webp',
      3 => '/images/products/ferretti-680/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '21m · 4 cabine · Skipper incluso',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 4000,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Superyacht',
  ),
  21 => 
  array (
    'id' => 'yc-02',
    'slug' => 'cranchi-43',
    'title' => 'Cranchi 43',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Cannigione',
    'pricePerDay' => 2900,
    'depositAmount' => 4000,
    'rating' => 4.7,
    'reviewsCount' => 34,
    'description' => 'Un open veloce e maneggevole, ideale per esplorare in giornata le calette del golfo di Arzachena e le isole dell\'arcipelago: quattordici metri con tre cabine e un pozzetto conviviale.

Disponibile con o senza skipper (patente nautica richiesta): la formula preferita da chi ama condurre in autonomia acque tra le più belle del Mediterraneo.',
    'amenities' => 
    array (
      0 => 'Pozzetto con dinette',
      1 => '3 cabine',
      2 => 'Prendisole di prua',
      3 => 'Doccia esterna',
      4 => 'Frigo e ghiacciaia',
      5 => 'GPS e autopilota',
      6 => 'Tendalino',
      7 => 'Scaletta bagno',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Briefing tecnico e cartografico',
      1 => 'Pulizia finale',
      2 => 'Assicurazione',
      3 => 'Dotazioni di sicurezza complete',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante a consumo',
      1 => 'Skipper (€250/giorno, su richiesta)',
      2 => 'Ormeggi esterni',
    ),
    'supplier' => 'Smeralda Charter',
    'map' => 
    array (
      'x' => 47,
      'y' => 50,
    ),
    'lnglat' => 
    array (
      0 => 9.44,
      1 => 41.104,
    ),
    'manufacturer' => 'Cranchi',
    'lengthMeters' => 14,
    'cabins' => 3,
    'bathrooms' => 2,
    'maxGuests' => 10,
    'hasSkipper' => false,
    'skipperIncluded' => false,
    'crewSize' => 0,
    'cruiseSpeedKnots' => 26,
    'fuelIncluded' => false,
    'marina' => 'Marina di Cannigione',
    'yachtType' => 'Open',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/cranchi-43/1.webp',
      1 => '/images/products/cranchi-43/2.webp',
      2 => '/images/products/cranchi-43/3.webp',
      3 => '/images/products/cranchi-43/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '14m · 3 cabine · Con o senza skipper',
    'location' => 'Cannigione, Costa Smeralda',
    'price' => 2900,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => false,
    'badge' => NULL,
    'subtype' => 'Day Cruiser',
  ),
  22 => 
  array (
    'id' => 'yc-03',
    'slug' => 'pardo-50',
    'title' => 'Pardo 50',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Cervo',
    'pricePerDay' => 3700,
    'depositAmount' => 5000,
    'rating' => 4.8,
    'reviewsCount' => 17,
    'description' => 'Il walkaround italiano più desiderato: linee pulite, coperta libera su un unico livello e due cabine eleganti. Il Pardo 50 è pensato per giornate lunghe tra le isole, tra tuffi e pranzi alla fonda.

Lo skipper incluso conosce ogni ridosso dell\'arcipelago e adatta la rotta a vento e affollamento, garantendo sempre la caletta giusta.',
    'amenities' => 
    array (
      0 => 'Coperta walkaround',
      1 => '2 cabine matrimoniali',
      2 => 'Prendisole XXL',
      3 => 'Cucina esterna con grill',
      4 => 'Doccia calda',
      5 => 'Stabilizzatore giroscopico',
      6 => 'Wi-Fi di bordo',
      7 => 'SUP e snorkeling',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper professionista',
      1 => 'Frutta e prosecco di benvenuto',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante (APA 25%)',
      1 => 'Chef di bordo (su richiesta)',
      2 => 'Ormeggi fuori porto base',
    ),
    'supplier' => 'Pevero Yachting',
    'map' => 
    array (
      'x' => 68,
      'y' => 47,
    ),
    'lnglat' => 
    array (
      0 => 9.5405,
      1 => 41.134,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Pardo',
    'lengthMeters' => 14,
    'cabins' => 2,
    'bathrooms' => 2,
    'maxGuests' => 9,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 30,
    'fuelIncluded' => false,
    'marina' => 'Marina di Porto Cervo',
    'yachtType' => 'Open',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/pardo-50/1.webp',
      1 => '/images/products/pardo-50/2.webp',
      2 => '/images/products/pardo-50/3.webp',
      3 => '/images/products/pardo-50/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '14m · 2 cabine · Skipper incluso',
    'location' => 'Porto Cervo, Costa Smeralda',
    'price' => 3700,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Day Cruiser',
  ),
  23 => 
  array (
    'id' => 'yc-04',
    'slug' => 'sanlorenzo-sl78',
    'title' => 'Sanlorenzo SL78',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Amalfi',
    'pricePerDay' => 7500,
    'depositAmount' => 12000,
    'rating' => 5,
    'reviewsCount' => 9,
    'description' => 'Ventiquattro metri di cantiere Sanlorenzo con equipaggio di tre persone: il modo più esclusivo di vivere la costiera, da Capri a Cetara, con pranzo stellato servito a bordo.

Quattro cabine, beach club a poppa e un armamento di toys completo: seabob, paddle e tender per raggiungere le grotte inaccessibili da terra.',
    'amenities' => 
    array (
      0 => 'Beach club a poppa',
      1 => '4 cabine con bagno',
      2 => 'Jacuzzi sul fly',
      3 => 'Seabob e water toys',
      4 => 'Stabilizzatori zero speed',
      5 => 'Cucina professionale',
      6 => 'Aria condizionata',
      7 => 'Cinema all\'aperto',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Equipaggio di 3 persone',
      1 => 'Chef di bordo a pranzo',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante (APA 30%)',
      1 => 'Ormeggi esterni',
      2 => 'Cene a bordo (su richiesta)',
    ),
    'supplier' => 'Amalfi Yachting',
    'map' => 
    array (
      'x' => 56,
      'y' => 44,
    ),
    'lnglat' => 
    array (
      0 => 14.6045,
      1 => 40.633,
    ),
    'badge' => 'EQUIPAGGIO COMPLETO',
    'manufacturer' => 'Sanlorenzo',
    'lengthMeters' => 24,
    'cabins' => 4,
    'bathrooms' => 4,
    'maxGuests' => 12,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 3,
    'cruiseSpeedKnots' => 24,
    'fuelIncluded' => false,
    'marina' => 'Porto di Amalfi',
    'yachtType' => 'Motor yacht',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/sanlorenzo-sl78/1.webp',
      1 => '/images/products/sanlorenzo-sl78/2.webp',
      2 => '/images/products/sanlorenzo-sl78/3.webp',
      3 => '/images/products/sanlorenzo-sl78/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '24m · 4 cabine · Skipper incluso',
    'location' => 'Amalfi, Costiera Amalfitana',
    'price' => 7500,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Superyacht',
  ),
  24 => 
  array (
    'id' => 'yc-05',
    'slug' => 'gozzo-apreamare-38',
    'title' => 'Apreamare Gozzo 38',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Positano',
    'pricePerDay' => 3100,
    'depositAmount' => 3500,
    'rating' => 4.8,
    'reviewsCount' => 29,
    'description' => 'Il gozzo sorrentino reinterpretato in chiave contemporanea: legno, linee morbide e un pozzetto enorme per vivere il mare della costiera come i capitani di una volta, ma con ogni comfort.

Lo skipper locale è una guida preziosa: grotte, bagni a Li Galli, pranzo da Lo Scoglio a Nerano. La barca perfetta per la giornata-tipo amalfitana.',
    'amenities' => 
    array (
      0 => 'Pozzetto conviviale in teak',
      1 => 'Prendisole di prua',
      2 => 'Cabina matrimoniale',
      3 => 'Doccia esterna',
      4 => 'Frigo e ghiacciaia',
      5 => 'Tendalino parasole',
      6 => 'Impianto audio',
      7 => 'Snorkeling incluso',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper locale',
      1 => 'Frutta e limoncello di benvenuto',
      2 => 'Carburante per itinerario standard',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Pranzi e prenotazioni ristorante',
      1 => 'Ore extra oltre le 8 giornaliere',
      2 => 'Ormeggi esterni',
    ),
    'supplier' => 'Positano Boats',
    'map' => 
    array (
      'x' => 32,
      'y' => 46,
    ),
    'lnglat' => 
    array (
      0 => 14.4855,
      1 => 40.6262,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Apreamare',
    'lengthMeters' => 11.5,
    'cabins' => 1,
    'bathrooms' => 1,
    'maxGuests' => 10,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 22,
    'fuelIncluded' => true,
    'marina' => 'Spiaggia Grande, Positano',
    'yachtType' => 'Gozzo',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/gozzo-apreamare-38/1.webp',
      1 => '/images/products/gozzo-apreamare-38/2.webp',
      2 => '/images/products/gozzo-apreamare-38/3.webp',
      3 => '/images/products/gozzo-apreamare-38/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '11.5m · 1 cabine · Skipper incluso',
    'location' => 'Positano, Costiera Amalfitana',
    'price' => 3100,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Runabout & Gozzo',
  ),
  25 => 
  array (
    'id' => 'yc-06',
    'slug' => 'riva-aquarama-super',
    'title' => 'Riva Aquarama Super',
    'destinationId' => 'lago-di-como',
    'locality' => 'Bellagio',
    'pricePerDay' => 3900,
    'depositAmount' => 8000,
    'rating' => 5,
    'reviewsCount' => 12,
    'description' => 'Il motoscafo più elegante mai costruito, nato proprio su queste acque: un Aquarama Super del 1970 restaurato dal cantiere, con mogano lucido e sellerie acquamarina originali.

Con il comandante in divisa, è il mezzo definitivo per un tour delle ville del lago, un arrivo di scena a una cerimonia o un servizio fotografico senza tempo.',
    'amenities' => 
    array (
      0 => 'Scafo in mogano originale',
      1 => 'Sellerie acquamarina',
      2 => '2 motori V8 Riva',
      3 => 'Prendisole di poppa',
      4 => 'Champagne cooler',
      5 => 'Coperte in cashmere',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Comandante in divisa',
      1 => 'Champagne di benvenuto',
      2 => 'Carburante per tour standard',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Ore extra oltre le 4 concordate',
      1 => 'Servizio fotografico (su richiesta)',
      2 => 'Attracchi privati',
    ),
    'supplier' => 'Lario Classic Boats',
    'map' => 
    array (
      'x' => 54,
      'y' => 42,
    ),
    'lnglat' => 
    array (
      0 => 9.2635,
      1 => 45.9845,
    ),
    'badge' => 'ICONA D\'EPOCA',
    'manufacturer' => 'Riva',
    'lengthMeters' => 8.8,
    'cabins' => 0,
    'bathrooms' => 0,
    'maxGuests' => 8,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 40,
    'fuelIncluded' => true,
    'marina' => 'Darsena di Bellagio',
    'yachtType' => 'Classica',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/riva-aquarama-super/1.webp',
      1 => '/images/products/riva-aquarama-super/2.webp',
      2 => '/images/products/riva-aquarama-super/3.webp',
      3 => '/images/products/riva-aquarama-super/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '8.8m · 0 cabine · Skipper incluso',
    'location' => 'Bellagio, Lago di Como',
    'price' => 3900,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Runabout & Gozzo',
  ),
  26 => 
  array (
    'id' => 'yc-07',
    'slug' => 'vandutch-40-como',
    'title' => 'VanDutch 40',
    'destinationId' => 'lago-di-como',
    'locality' => 'Como',
    'pricePerDay' => 3400,
    'depositAmount' => 5000,
    'rating' => 4.7,
    'reviewsCount' => 15,
    'description' => 'Linee minimali e coperta completamente vivibile: il VanDutch 40 è il day cruiser contemporaneo per eccellenza, perfetto per aperitivi al tramonto davanti a Villa Oleandra.

Dodici metri silenziosi e stabili, con skipper e hostess su richiesta per un servizio da beach club galleggiante.',
    'amenities' => 
    array (
      0 => 'Coperta open completamente vivibile',
      1 => 'Prendisole XL',
      2 => 'Cabina con bagno',
      3 => 'Frigo champagne',
      4 => 'Impianto audio premium',
      5 => 'Doccia esterna',
      6 => 'Tendalino elettrico',
      7 => 'Passerella idraulica',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper',
      1 => 'Acqua e soft drink',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante a consumo',
      1 => 'Hostess (su richiesta)',
      2 => 'Catering a bordo',
    ),
    'supplier' => 'Como Lake Cruises',
    'map' => 
    array (
      'x' => 34,
      'y' => 72,
    ),
    'lnglat' => 
    array (
      0 => 9.091,
      1 => 45.8165,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'VanDutch',
    'lengthMeters' => 12.2,
    'cabins' => 1,
    'bathrooms' => 1,
    'maxGuests' => 10,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 28,
    'fuelIncluded' => false,
    'marina' => 'Como, Viale Geno',
    'yachtType' => 'Open',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/vandutch-40-como/1.webp',
      1 => '/images/products/vandutch-40-como/2.webp',
      2 => '/images/products/vandutch-40-como/3.webp',
      3 => '/images/products/vandutch-40-como/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '12.2m · 1 cabine · Skipper incluso',
    'location' => 'Como, Lago di Como',
    'price' => 3400,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Runabout & Gozzo',
  ),
  27 => 
  array (
    'id' => 'yc-08',
    'slug' => 'pershing-7x',
    'title' => 'Pershing 7X',
    'destinationId' => 'costa-smeralda',
    'locality' => 'Porto Rotondo',
    'pricePerDay' => 6500,
    'depositAmount' => 10000,
    'rating' => 4.9,
    'reviewsCount' => 8,
    'description' => 'Venti metri di pura adrenalina: il Pershing 7X è il day boat sportivo per eccellenza, capace di sfiorare i quaranta nodi per raggiungere La Maddalena in un soffio e passare la giornata alla fonda tra Spargi e Budelli.

Linee aggressive in carbonio, tre cabine per gli ospiti e un pozzetto conviviale con prendisole a poppa. L\'equipaggio di due persone cura rotta, aperitivi e prenotazioni nei ristoranti sul mare.',
    'amenities' => 
    array (
      0 => 'Carena da 42 nodi',
      1 => '3 cabine ospiti',
      2 => 'Prendisole di poppa e prua',
      3 => 'Beach platform idraulica',
      4 => 'Tender e attrezzatura snorkeling',
      5 => 'Impianto audio esterno',
      6 => 'Aria condizionata',
      7 => 'Cucina completa',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper e marinaio',
      1 => 'Aperitivo di benvenuto',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante (APA 30%)',
      1 => 'Ormeggi fuori porto base',
      2 => 'Chef di bordo (su richiesta)',
    ),
    'supplier' => 'Pevero Yachting',
    'map' => 
    array (
      'x' => 72,
      'y' => 64,
    ),
    'lnglat' => 
    array (
      0 => 9.548,
      1 => 41.03,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Pershing',
    'lengthMeters' => 20,
    'cabins' => 3,
    'bathrooms' => 3,
    'maxGuests' => 12,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 2,
    'cruiseSpeedKnots' => 42,
    'fuelIncluded' => false,
    'marina' => 'Marina di Porto Rotondo',
    'yachtType' => 'Motor yacht',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/pershing-7x/1.webp',
      1 => '/images/products/pershing-7x/2.webp',
      2 => '/images/products/pershing-7x/3.webp',
      3 => '/images/products/pershing-7x/4.webp',
    ),
    'destination' => 'costa-smeralda',
    'subtitle' => '20m · 3 cabine · Skipper incluso',
    'location' => 'Porto Rotondo, Costa Smeralda',
    'price' => 6500,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Superyacht',
  ),
  28 => 
  array (
    'id' => 'yc-09',
    'slug' => 'wallytender-48',
    'title' => 'Wallytender 48',
    'destinationId' => 'costiera-amalfitana',
    'locality' => 'Capri',
    'pricePerDay' => 4200,
    'depositAmount' => 7000,
    'rating' => 4.9,
    'reviewsCount' => 10,
    'description' => 'L\'icona del design navale contemporaneo: il Wallytender 48 unisce le linee taglienti del marchio a una coperta vivibilissima, pensata per la giornata perfetta intorno a Capri, tra i Faraglioni, la Grotta Azzurra e i bagni a Marina Piccola.

Quindici metri stabili e silenziosi, con skipper che conosce ogni ridosso dell\'isola e adatta la rotta al vento e all\'affollamento, garantendo sempre l\'ancoraggio giusto.',
    'amenities' => 
    array (
      0 => 'Coperta walkaround',
      1 => 'Cabina matrimoniale',
      2 => 'Prendisole XXL di prua',
      3 => 'Beach platform a poppa',
      4 => 'Doccia esterna',
      5 => 'Frigo e ghiacciaia',
      6 => 'Impianto audio premium',
      7 => 'SUP e snorkeling',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper professionista',
      1 => 'Frutta e prosecco di benvenuto',
      2 => 'Pulizia finale',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Carburante (APA 25%)',
      1 => 'Pranzi e prenotazioni ristorante',
      2 => 'Ormeggi fuori porto base',
    ),
    'supplier' => 'Amalfi Yachting',
    'map' => 
    array (
      'x' => 12,
      'y' => 66,
    ),
    'lnglat' => 
    array (
      0 => 14.243,
      1 => 40.553,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Wally',
    'lengthMeters' => 14.7,
    'cabins' => 1,
    'bathrooms' => 1,
    'maxGuests' => 12,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 34,
    'fuelIncluded' => false,
    'marina' => 'Marina Grande, Capri',
    'yachtType' => 'Open',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/wallytender-48/1.webp',
      1 => '/images/products/wallytender-48/2.webp',
      2 => '/images/products/wallytender-48/3.webp',
      3 => '/images/products/wallytender-48/4.webp',
    ),
    'destination' => 'costiera-amalfitana',
    'subtitle' => '14.7m · 1 cabine · Skipper incluso',
    'location' => 'Capri, Costiera Amalfitana',
    'price' => 4200,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Day Cruiser',
  ),
  29 => 
  array (
    'id' => 'yc-10',
    'slug' => 'frauscher-858-fantom',
    'title' => 'Frauscher 858 Fantom',
    'destinationId' => 'lago-di-como',
    'locality' => 'Menaggio',
    'pricePerDay' => 3200,
    'depositAmount' => 5000,
    'rating' => 4.8,
    'reviewsCount' => 13,
    'description' => 'Gli austriaci di Frauscher hanno reinventato il day boat con linee da yacht in miniatura: l\'858 Fantom scivola quasi in silenzio sull\'acqua del Lario, perfetto per un aperitivo al tramonto davanti a Villa Carlotta o un tour delle dimore storiche.

Otto metri e mezzo di eleganza minimale con prendisole di prua e pozzetto conviviale; lo skipper accompagna gli ospiti tra Menaggio, Bellagio e Varenna, il celebre "centro lago".',
    'amenities' => 
    array (
      0 => 'Carena sportiva',
      1 => 'Prendisole di prua',
      2 => 'Pozzetto conviviale',
      3 => 'Impianto audio premium',
      4 => 'Frigo di bordo',
      5 => 'Doccia esterna',
      6 => 'Piattaforma bagno',
      7 => 'Snorkeling incluso',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Skipper',
      1 => 'Prosecco e soft drink di benvenuto',
      2 => 'Carburante per tour standard',
      3 => 'Assicurazione',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Ore extra oltre le concordate',
      1 => 'Catering a bordo',
      2 => 'Attracchi privati',
    ),
    'supplier' => 'Como Lake Cruises',
    'map' => 
    array (
      'x' => 30,
      'y' => 24,
    ),
    'lnglat' => 
    array (
      0 => 9.234,
      1 => 46.022,
    ),
    'badge' => 'CON SKIPPER',
    'manufacturer' => 'Frauscher',
    'lengthMeters' => 8.5,
    'cabins' => 0,
    'bathrooms' => 0,
    'maxGuests' => 8,
    'hasSkipper' => true,
    'skipperIncluded' => true,
    'crewSize' => 1,
    'cruiseSpeedKnots' => 38,
    'fuelIncluded' => true,
    'marina' => 'Menaggio, Lungolago',
    'yachtType' => 'Open',
    'category' => 'yacht',
    'images' => 
    array (
      0 => '/images/products/frauscher-858-fantom/1.webp',
      1 => '/images/products/frauscher-858-fantom/2.webp',
      2 => '/images/products/frauscher-858-fantom/3.webp',
      3 => '/images/products/frauscher-858-fantom/4.webp',
    ),
    'destination' => 'lago-di-como',
    'subtitle' => '8.5m · 0 cabine · Skipper incluso',
    'location' => 'Menaggio, Lago di Como',
    'price' => 3200,
    'unit' => 'giorno',
    'capacity' => 10,
    'capacityLabel' => '10 ospiti',
    'featured' => true,
    'subtype' => 'Runabout & Gozzo',
  ),
  30 => 
  array (
    'id' => 'jt-01',
    'slug' => 'gulfstream-g650er-olbia',
    'category' => 'jet',
    'destination' => 'costa-smeralda',
    'title' => 'Gulfstream G650ER Flagship',
    'subtitle' => 'Heavy Jet intercontinentale · 14 passeggeri · Cabina Bespoke',
    'location' => 'Olbia Costa Smeralda (OLB) · Eccelsa Aviation',
    'price' => 14500,
    'unit' => 'tratta',
    'capacity' => 14,
    'capacityLabel' => '14 passeggeri',
    'featured' => true,
    'badge' => 'Più richiesto',
    'subtype' => 'Heavy Jet',
    'lnglat' => 
    array (
      0 => 9.5176,
      1 => 40.8987,
    ),
    'map' => 
    array (
      'x' => 54,
      'y' => 64,
    ),
    'images' => 
    array (
      0 => '/images/products/gulfstream-g650er-olbia/1.webp',
      1 => '/images/products/gulfstream-g650er-olbia/2.webp',
      2 => '/images/products/gulfstream-g650er-olbia/3.webp',
      3 => '/images/products/gulfstream-g650er-olbia/4.webp',
    ),
    'description' => 'L\'ammiraglia dell\'aviazione privata mondiale per raggiungere la Costa Smeralda senza scali da qualunque capitale europea o intercontinentale. Cabina silenziosa pressurizzata a quota ridotta, sedute in pelle Poltrona Frau convertibili in letti piani, connettività satellitare Ka-band ad alta velocità e accoglienza dedicata presso il terminal VIP Eccelsa Aviation di Olbia con trasferimento diretto sottobordo.',
    'amenities' => 
    array (
      0 => 'Terminal VIP dedicato Eccelsa Aviation',
      1 => 'Catering stellato personalizzato a bordo',
      2 => 'Wi-Fi Satellitare Ka-Band ad alta velocità',
      3 => 'Cabina configurabile giorno/notte (6 letti)',
      4 => 'Flight Attendant dedicato e 2 Comandanti Senior',
      5 => 'Coordinamento slot prioritari e bagagli illimitati',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Tasse aeroportuali e handling VIP Eccelsa',
      1 => 'Open bar Champagne Dom Pérignon e catering gourmet',
      2 => 'Assistenza doganale fast-track sottobordo',
      3 => 'Consegna auto o tender direttamente in rampa',
    ),
    'servicesExcluded' => 
    array (
      0 => 'De-icing invernale (se applicabile)',
      1 => 'Riposizionamento su scali fuori rete europea',
    ),
    'rules' => 
    array (
      0 => 'Presentazione in terminal VIP 20 minuti prima del decollo',
      1 => 'Animali domestici ammessi in cabina con documentazione sanitaria',
      2 => 'Fumo consentito solo su richiesta preventiva con sanificazione ozono',
    ),
    'cancellationPolicy' => 'Cancellazione gratuita fino a 72 ore prima dello slot di volo confermato.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Heavy Jet',
    'rangeKm' => 13890,
    'cruiseSpeedKmh' => 956,
    'heliportTransfer' => true,
    'cabinCrew' => '2 Piloti + 2 Assistenti di Volo',
  ),
  31 => 
  array (
    'id' => 'jt-02',
    'slug' => 'pilatus-pc-24-costa-smeralda',
    'category' => 'jet',
    'destination' => 'costa-smeralda',
    'title' => 'Pilatus PC-24 Super Versatile Jet',
    'subtitle' => 'Super Light Jet · 8 passeggeri · Porta Cargo XL per bagagli e tender toys',
    'location' => 'Olbia Costa Smeralda (OLB) · Porto Cervo',
    'price' => 6800,
    'unit' => 'tratta',
    'capacity' => 8,
    'capacityLabel' => '8 passeggeri',
    'featured' => false,
    'subtype' => 'Super Light Jet',
    'lnglat' => 
    array (
      0 => 9.528,
      1 => 41.095,
    ),
    'map' => 
    array (
      'x' => 56,
      'y' => 42,
    ),
    'images' => 
    array (
      0 => '/images/products/pilatus-pc-24-costa-smeralda/1.webp',
      1 => '/images/products/pilatus-pc-24-costa-smeralda/2.webp',
      2 => '/images/products/pilatus-pc-24-costa-smeralda/3.webp',
      3 => '/images/products/pilatus-pc-24-costa-smeralda/4.webp',
    ),
    'description' => 'Il jet svizzero progettato per collegare Ginevra, Zurigo, Milano Linate, Parigi e Londra direttamente con Olbia in totale flessibilità. Dotato di ampia porta cargo pressurizzata ideale per bauli, attrezzatura subacquea o golf bag, e interni firmati BMW Designworks.',
    'amenities' => 
    array (
      0 => 'Interni Executive in pelle pieno fiore',
      1 => 'Porta bagagli XL accessibile in volo',
      2 => 'Imbarco sottobordo senza attese',
      3 => 'Bar privato e rinfreschi sartoriali',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Handling VIP in partenza e arrivo',
      1 => 'Refreshment di bordo e champagne',
      2 => 'Trasferimento bagagli diretto in villa',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Trasferimento elicottero (prenotabile in abbinamento)',
    ),
    'rules' => 
    array (
      0 => 'Partenza flessibile con finestra slot dedicata',
      1 => 'Animali ammessi in cabina',
    ),
    'cancellationPolicy' => 'Cancellazione gratuita fino a 48 ore prima del volo.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Super Light Jet',
    'rangeKm' => 3700,
    'cruiseSpeedKmh' => 815,
    'heliportTransfer' => false,
    'cabinCrew' => '2 Piloti + Hostess su richiesta',
  ),
  32 => 
  array (
    'id' => 'jt-03',
    'slug' => 'agustawestland-aw139-heli-vip',
    'category' => 'jet',
    'destination' => 'costa-smeralda',
    'title' => 'AgustaWestland AW139 VIP Heli',
    'subtitle' => 'Elicottero Bimotore VIP · 6 passeggeri · Atterraggio diretto in Villa o Superyacht',
    'location' => 'Porto Cervo · Cala di Volpe · Eliporto Privato',
    'price' => 4200,
    'unit' => 'tratta',
    'capacity' => 6,
    'capacityLabel' => '6 passeggeri',
    'featured' => true,
    'badge' => 'Atterraggio in Villa',
    'subtype' => 'Elicottero VIP',
    'lnglat' => 
    array (
      0 => 9.539,
      1 => 41.124,
    ),
    'map' => 
    array (
      'x' => 53,
      'y' => 32,
    ),
    'images' => 
    array (
      0 => '/images/products/agustawestland-aw139-heli-vip/1.webp',
      1 => '/images/products/agustawestland-aw139-heli-vip/2.webp',
      2 => '/images/products/agustawestland-aw139-heli-vip/3.webp',
      3 => '/images/products/agustawestland-aw139-heli-vip/4.webp',
    ),
    'description' => 'Il riferimento assoluto nel trasporto elicotteristico presidenziale e VIP. L\'AW139 biturbina collega in soli 8 minuti l\'aeroporto di Olbia con le elisuperfici private delle ville di Porto Cervo, Cala di Volpe, Porto Rotondo o il ponte di volo del tuo yacht, oltre a voli panoramici verso l\'Arcipelago di La Maddalena e la Corsica del Sud.',
    'amenities' => 
    array (
      0 => 'Cabina insonorizzata VIP 6 poltrone club',
      1 => 'Doppio motore turbina Pratt & Whitney',
      2 => 'Cuffie Bose A20 con cancellazione attiva del rumore',
      3 => 'Aria condizionata dedicata e frigobar',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Doppio pilota certificato IFR/Notturno',
      1 => 'Autorizzazioni atterraggio su elisuperfici private',
      2 => 'Coordinamento immediato con l\'atterraggio del tuo jet privato',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Operazioni oltre il tramonto su elisuperfici non certificate notturne',
    ),
    'rules' => 
    array (
      0 => 'Bagagli morbidi consigliati per stiva veloce',
      1 => 'Soggetto a condizioni meteo-visibilità minime ENAC',
    ),
    'cancellationPolicy' => 'Rimborso integrale in caso di condizioni meteo avverse; cancellazione libera fino a 24 ore prima.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Elicottero VIP',
    'rangeKm' => 1060,
    'cruiseSpeedKmh' => 306,
    'heliportTransfer' => true,
    'cabinCrew' => '2 Piloti Comandanti',
  ),
  33 => 
  array (
    'id' => 'jt-04',
    'slug' => 'bombardier-challenger-350-napoli',
    'category' => 'jet',
    'destination' => 'costiera-amalfitana',
    'title' => 'Bombardier Challenger 350',
    'subtitle' => 'Super Midsize Jet · 10 passeggeri · Cabina Stand-Up Flat Floor',
    'location' => 'Napoli Capodichino VIP / Salerno Costa d\'Amalfi',
    'price' => 9800,
    'unit' => 'tratta',
    'capacity' => 10,
    'capacityLabel' => '10 passeggeri',
    'featured' => true,
    'badge' => 'Più richiesto',
    'subtype' => 'Super Midsize Jet',
    'lnglat' => 
    array (
      0 => 14.602,
      1 => 40.642,
    ),
    'map' => 
    array (
      'x' => 63,
      'y' => 42,
    ),
    'images' => 
    array (
      0 => '/images/products/bombardier-challenger-350-napoli/1.webp',
      1 => '/images/products/bombardier-challenger-350-napoli/2.webp',
      2 => '/images/products/bombardier-challenger-350-napoli/3.webp',
      3 => '/images/products/bombardier-challenger-350-napoli/4.webp',
    ),
    'description' => 'Il Super Midsize Jet più amato per raggiungere la Costiera Amalfitana e Capri. Atterraggio presso il nuovo scalo di Salerno Costa d\'Amalfi o il terminal aviazione generale di Napoli, con cabina ad altezza d\'uomo, finestrini panoramici maggiorati e collegamento immediato in elicottero o motoscafo veloce verso Positano e Ravello.',
    'amenities' => 
    array (
      0 => 'Cabina Stand-Up a pavimento piatto',
      1 => 'Sistema audio Lufthansa Technik Nice HD',
      2 => 'Galley completa per servizio caldo stellato',
      3 => 'Accesso diretto alla stiva bagagli durante il volo',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Fast-track VIP e lounge privata',
      1 => 'Catering campano d\'autore e selezione vini riserva',
      2 => 'Meet & Greet sottobordo con autista o pilota elicottero',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Trasferimento in elicottero verso Ravello/Positano (aggiungibile nella composizione)',
    ),
    'rules' => 
    array (
      0 => 'Presentazione 20 minuti prima del decollo',
      1 => 'Animali domestici benvenuti a bordo',
    ),
    'cancellationPolicy' => 'Cancellazione gratuita fino a 48 ore prima dello slot.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Super Midsize Jet',
    'rangeKm' => 5926,
    'cruiseSpeedKmh' => 870,
    'heliportTransfer' => true,
    'cabinCrew' => '2 Piloti + 1 Flight Attendant',
  ),
  34 => 
  array (
    'id' => 'jt-05',
    'slug' => 'airbus-ach145-positano-capri',
    'category' => 'jet',
    'destination' => 'costiera-amalfitana',
    'title' => 'Airbus ACH145 Mercedes-Benz Style',
    'subtitle' => 'Elicottero Bimotore VIP · 7 passeggeri · Transfer Napoli/Salerno - Positano, Ravello e Capri',
    'location' => 'Ravello · Positano · Capri Damecuta',
    'price' => 3900,
    'unit' => 'tratta',
    'capacity' => 7,
    'capacityLabel' => '7 passeggeri',
    'featured' => false,
    'badge' => 'Transfer Rapido',
    'subtype' => 'Elicottero VIP',
    'lnglat' => 
    array (
      0 => 14.492,
      1 => 40.631,
    ),
    'map' => 
    array (
      'x' => 30,
      'y' => 50,
    ),
    'images' => 
    array (
      0 => '/images/products/airbus-ach145-positano-capri/1.webp',
      1 => '/images/products/airbus-ach145-positano-capri/2.webp',
      2 => '/images/products/airbus-ach145-positano-capri/3.webp',
      3 => '/images/products/airbus-ach145-positano-capri/4.webp',
    ),
    'description' => 'Evita completamente il traffico costiero: in soli 14 minuti di volo panoramico sul Golfo di Napoli e sui Faraglioni, l\'Airbus ACH145 in edizione Mercedes-Benz Style collega gli aeroporti di Napoli e Salerno con le elisuperfici di Ravello, Positano, Sorrento e Anacapri.',
    'amenities' => 
    array (
      0 => 'Interni firmati Mercedes-Benz Advanced Design',
      1 => 'Rotore di coda Fenestron ultra-silenzioso',
      2 => 'Vetrate panoramiche a tutta altezza',
      3 => 'Climatizzazione tropicalizzata e champagne cooler',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Tasse di atterraggio sulle elisuperfici della Costiera e Capri',
      1 => 'Trasferimento bagagli dedicato via terra/mare fino in villa',
      2 => 'Doppio pilota comandante esperto dell\'orografia costiera',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Voli notturni dopo le ore 20:30',
    ),
    'rules' => 
    array (
      0 => 'Documento d\'identità per tutti i passeggeri prima dell\'imbarco',
    ),
    'cancellationPolicy' => 'Cancellazione gratuita fino a 24 ore prima del decollo.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Elicottero VIP',
    'rangeKm' => 660,
    'cruiseSpeedKmh' => 250,
    'heliportTransfer' => true,
    'cabinCrew' => '2 Piloti Comandanti',
  ),
  35 => 
  array (
    'id' => 'jt-06',
    'slug' => 'cessna-citation-longitude-como',
    'category' => 'jet',
    'destination' => 'lago-di-como',
    'title' => 'Cessna Citation Longitude + Heli Lario',
    'subtitle' => 'Super Midsize Jet + Helitransfer · 9 passeggeri · Milano Linate Prime → Lago di Como',
    'location' => 'Milano Linate Prime / Cernobbio & Bellagio',
    'price' => 8900,
    'unit' => 'tratta',
    'capacity' => 9,
    'capacityLabel' => '9 passeggeri',
    'featured' => true,
    'badge' => 'Jet + Heli Incluso',
    'subtype' => 'Super Midsize Jet',
    'lnglat' => 
    array (
      0 => 9.255,
      1 => 45.981,
    ),
    'map' => 
    array (
      'x' => 56,
      'y' => 44,
    ),
    'images' => 
    array (
      0 => '/images/products/cessna-citation-longitude-como/1.webp',
      1 => '/images/products/cessna-citation-longitude-como/2.webp',
      2 => '/images/products/cessna-citation-longitude-como/3.webp',
      3 => '/images/products/cessna-citation-longitude-como/4.webp',
    ),
    'description' => 'La cabina più silenziosa della categoria Super Midsize abbinata al collegamento elicotteristico immediato da Milano Linate Prime o Lugano Agno direttamente alle elisuperfici private di Cernobbio, Tremezzo e Bellagio sul Lago di Como.',
    'amenities' => 
    array (
      0 => 'Cabina più silenziosa della classe (67 dBA)',
      1 => 'Connettività Wi-Fi e controllo cabina da iPad',
      2 => 'Coincidenza elicottero garantita sulla stessa rampa a Linate Prime',
      3 => 'Servizio maggiordomo per consegna bagagli in villa sul Lario',
    ),
    'servicesIncluded' => 
    array (
      0 => 'Volo Jet + connessione elicottero o limousine Maybach verso il Lago di Como',
      1 => 'Catering gourmet e selezione Franciacorta Riserva',
      2 => 'VIP Lounge privata a Milano Linate Prime',
    ),
    'servicesExcluded' => 
    array (
      0 => 'Eventuali pernottamenti equipaggio per soste superiori a 5 giorni',
    ),
    'rules' => 
    array (
      0 => 'Orario di partenza personalizzabile fino a 3 ore prima del volo',
    ),
    'cancellationPolicy' => 'Cancellazione gratuita fino a 48 ore prima del volo.',
    'supplier' => 'Mosaic Sky Aviation',
    'aircraftType' => 'Super Midsize Jet',
    'rangeKm' => 6482,
    'cruiseSpeedKmh' => 895,
    'heliportTransfer' => true,
    'cabinCrew' => '2 Piloti + 1 Assistente di Volo',
  ),
);

        $extraKeys = [
            'bedrooms', 'beds', 'bathrooms', 'maxGuests', 'sqm', 'pool', 'seaView', 'seaAccess', 'staff', 'chefAvailable',
            'make', 'model', 'year', 'seats', 'transmission', 'fuelType', 'horsepower', 'power', 'kmIncluded', 'deliveryIncluded', 'autoType',
            'manufacturer', 'lengthMeters', 'cabins', 'hasSkipper', 'skipperIncluded', 'crewSize', 'cruiseSpeedKnots', 'fuelIncluded', 'marina', 'yachtType',
            'aircraftType', 'rangeKm', 'cruiseSpeedKmh', 'heliportTransfer', 'cabinCrew',
            'depositAmount', 'rating', 'reviewsCount', 'locality',
        ];

        $defaultRulesByCat = [
            'villa' => [
                'Check-in riservato dalle ore 16:00 con Welcome Concierge in villa',
                'Check-out entro le ore 11:00 (late check-out su richiesta)',
                'Eventi privati o ricevimenti consentiti solo previa autorizzazione scritta',
                'Staff giornaliero discreto incluso secondo gli orari concordati',
            ],
            'auto' => [
                'Età minima del conducente: 25 anni, patente da almeno 5 anni',
                'Secondo conducente incluso senza costi aggiuntivi',
                'Divieto di fumo a bordo',
                'Carburante: riconsegna con lo stesso livello del ritiro',
            ],
            'yacht' => [
                'Imbarco dalle ore 10:00 presso la marina di base concordata',
                'Itinerario giornaliero modulabile con il Comandante in base al meteo marino',
                'Calzature da ponte morbide o piedi scalzi richiesti a bordo',
                'Documenti di identità di tutti gli ospiti richiesti prima di mollare gli ormeggi',
            ],
            'jet' => [
                'Presentazione al terminal VIP (FBO) solo 20 minuti prima del decollo',
                'Documenti di identità o passaporto richiesti per il manifesto di volo',
            ],
        ];

        $defaultCancelByCat = [
            'villa' => 'Cancellazione gratuita fino a 30 giorni dall\'arrivo; entro 30 giorni trattenuto il 50% a copertura della proprietà.',
            'auto' => 'Cancellazione gratuita fino a 7 giorni dall\'inizio; entro 7 giorni trattenuto il 30% a copertura del fornitore.',
            'yacht' => 'Cancellazione gratuita fino a 14 giorni dall\'imbarco; riprogrammazione o rimborso integrale in caso di condizioni meteo-marine avverse certificate dalla Capitaneria.',
            'jet' => 'Cancellazione gratuita fino a 48 ore prima dello slot di volo.',
        ];

        foreach ($catalog as $item) {
            $extra = [];
            foreach ($extraKeys as $k) {
                if (array_key_exists($k, $item)) {
                    $extra[$k] = $item[$k];
                }
            }
            if (!empty($item['horsepower']) && empty($extra['power'])) {
                $extra['power'] = $item['horsepower'] . ' CV';
            }

            $subtitle = $item['subtitle'];
            if ($item['category'] === 'auto' && !empty($item['horsepower'])) {
                $subtitle = sprintf(
                    '%d CV · %s · %d posti',
                    (int)$item['horsepower'],
                    $item['transmission'] ?? 'Automatico',
                    (int)($item['seats'] ?? $item['capacity'])
                );
            }

            $rules = !empty($item['rules'])
                ? $item['rules']
                : ($defaultRulesByCat[$item['category']] ?? $defaultRulesByCat['villa']);

            $cancelPolicy = !empty($item['cancellationPolicy'])
                ? $item['cancellationPolicy']
                : ($defaultCancelByCat[$item['category']] ?? $defaultCancelByCat['villa']);

            Database::insert('products', [
                'code' => $item['id'],
                'slug' => $item['slug'],
                'category' => $item['category'],
                'destination_id' => $destMap[$item['destination']] ?? null,
                'destination_slug' => $item['destination'],
                'supplier_id' => $supMap[$item['supplier'] ?? ''] ?? null,
                'title' => $item['title'],
                'subtitle' => $subtitle,
                'location' => $item['location'],
                'price' => (int)$item['price'],
                'unit' => $item['unit'],
                'capacity' => (int)$item['capacity'],
                'capacity_label' => $item['capacityLabel'],
                'featured' => !empty($item['featured']) ? 1 : 0,
                'is_available' => 1,
                'badge' => $item['badge'] ?? null,
                'subtype' => $item['subtype'] ?? null,
                'lng' => (float)($item['lnglat'][0] ?? 9.53),
                'lat' => (float)($item['lnglat'][1] ?? 41.12),
                'map_x' => (float)($item['map']['x'] ?? 50),
                'map_y' => (float)($item['map']['y'] ?? 50),
                'description' => $item['description'] ?? '',
                'cancellation_policy' => $cancelPolicy,
                'images_json' => json_encode($item['images'] ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'amenities_json' => json_encode($item['amenities'] ?? [], JSON_UNESCAPED_UNICODE),
                'services_included_json' => json_encode($item['servicesIncluded'] ?? [], JSON_UNESCAPED_UNICODE),
                'services_excluded_json' => json_encode($item['servicesExcluded'] ?? [], JSON_UNESCAPED_UNICODE),
                'rules_json' => json_encode($rules, JSON_UNESCAPED_UNICODE),
                'specs_extra_json' => json_encode($extra, JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}