<?php

use App\Http\Controllers\CatalogController;

/** @var \App\Core\Router $router */

$router->get('/api/catalog', [CatalogController::class, 'apiCatalog']);
$router->post('/api/ai/concierge', [CatalogController::class, 'apiAiConcierge']);
$router->get('/api/ai/concierge', [CatalogController::class, 'apiAiConcierge']);
$router->post('/api/bookings', [CatalogController::class, 'submitRequest']);
