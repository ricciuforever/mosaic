<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\SupplierPortalController;

/** @var \App\Core\Router $router */

// Catalogo pubblico ed esperienza di composizione
$router->get('/', [CatalogController::class, 'home']);
$router->get('/compose', [CatalogController::class, 'compose']);
$router->get('/prodotto/{slug}', [CatalogController::class, 'productDetail']);
$router->get('/richiesta', [CatalogController::class, 'requestPage']);
$router->post('/richiesta', [CatalogController::class, 'submitRequest']);

// Autenticazione & Switch Ruolo RBAC
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/logout', [AuthController::class, 'logout']);

// Console Admin & Concierge Manager (protetta da Spatie RBAC)
$router->get('/admin', [AdminController::class, 'dashboard'], ['role:Super Admin|Concierge Manager']);
$router->post('/admin/bookings/{id}/offer', [AdminController::class, 'updateBookingOffer'], ['permission:bookings.manage_status|bookings.create_custom_offer']);
$router->post('/admin/products/save', [AdminController::class, 'storeOrUpdateProduct'], ['permission:catalog.create|catalog.edit']);
$router->post('/admin/products/{id}/toggle', [AdminController::class, 'toggleProductAvailability'], ['permission:catalog.edit']);
$router->post('/admin/suppliers/toggle-portal', [AdminController::class, 'toggleSupplierPortalMode'], ['permission:suppliers.toggle_standby|settings.manage']);
$router->post('/admin/roles/{id}/permissions', [AdminController::class, 'updateRolePermissions'], ['permission:roles.manage']);

// Portale Fornitore & Partner Hub (con Standby Gate & RBAC)
$router->get('/portale-fornitore', [SupplierPortalController::class, 'portal'], ['supplier.gate']);
$router->post('/portale-fornitore/items/{id}/status', [SupplierPortalController::class, 'updateItemStatus'], ['permission:portal.supplier.access|bookings.manage_status']);

// Area Clienti VIP (protetta da Spatie RBAC)
$router->get('/area-clienti', [ClientPortalController::class, 'portal'], ['permission:portal.client.access|bookings.view']);
$router->post('/area-clienti/bookings/{id}/accept', [ClientPortalController::class, 'acceptOffer'], ['permission:bookings.accept_offer|bookings.manage_status']);
