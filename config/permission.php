<?php

$defaultPermissions = [
    'admin.access' => ['group' => 'admin', 'description' => 'Accesso alla Console di Amministrazione'],
    'bookings.view' => ['group' => 'bookings', 'description' => 'Visualizzazione richieste ed esperienze clienti'],
    'bookings.manage_status' => ['group' => 'bookings', 'description' => 'Aggiornamento stato prenotazione (Nuova, Offerta, Confermata, Pagata, Annullata)'],
    'bookings.create_custom_offer' => ['group' => 'bookings', 'description' => 'Creazione preventivi su misura e sconti riservati'],
    'bookings.accept_offer' => ['group' => 'bookings', 'description' => 'Accettazione offerta personalizzata del Concierge'],
    'catalog.view' => ['group' => 'catalog', 'description' => 'Visualizzazione catalogo Ville, Auto, Yacht e Jet'],
    'catalog.create' => ['group' => 'catalog', 'description' => 'Inserimento nuove offerte a catalogo'],
    'catalog.edit' => ['group' => 'catalog', 'description' => 'Modifica schede tecniche, prezzi e disponibilità catalogo'],
    'catalog.delete' => ['group' => 'catalog', 'description' => 'Rimozione offerte dal catalogo'],
    'suppliers.view' => ['group' => 'suppliers', 'description' => 'Visualizzazione anagrafica Fornitori e Partner'],
    'suppliers.manage' => ['group' => 'suppliers', 'description' => 'Gestione contratti, commissioni e verifiche Fornitori'],
    'suppliers.toggle_standby' => ['group' => 'suppliers', 'description' => 'Switch stato Portale Fornitore (In Standby / Operativo)'],
    'portal.supplier.access' => ['group' => 'portals', 'description' => 'Accesso al Portale Fornitore & Partner Hub'],
    'portal.client.access' => ['group' => 'portals', 'description' => 'Accesso all\'Area Clienti VIP'],
    'roles.manage' => ['group' => 'rbac', 'description' => 'Gestione ruoli e matrice permessi Spatie RBAC'],
    'settings.manage' => ['group' => 'system', 'description' => 'Configurazione parametri globali e AI Orchestrator'],
];

$defaultRoles = [
    'Super Admin' => [
        'badge_color' => '#c6994f',
        'description' => 'Controllo completo di piattaforma, catalogo, prenotazioni, preventivi, fornitori e matrice RBAC.',
        'permissions' => array_keys($defaultPermissions),
    ],
    'Concierge Manager' => [
        'badge_color' => '#60a5fa',
        'description' => 'Gestisce richieste clienti, compone offerte personalizzate/sconti, aggiorna stati prenotazione e disponibilità catalogo.',
        'permissions' => [
            'admin.access',
            'bookings.view',
            'bookings.manage_status',
            'bookings.create_custom_offer',
            'catalog.view',
            'catalog.create',
            'catalog.edit',
            'suppliers.view',
            'portal.supplier.access',
            'portal.client.access',
        ],
    ],
    'Fornitore/Partner' => [
        'badge_color' => '#34d399',
        'description' => 'Accede al Portale Fornitori, visualizza la propria flotta/dimore associate (supplier_id) e conferma la disponibilità.',
        'permissions' => [
            'portal.supplier.access',
            'catalog.view',
            'bookings.view',
        ],
    ],
    'Cliente VIP' => [
        'badge_color' => '#e0b970',
        'description' => 'Compone esperienze multi-categoria, invia richieste al concierge, visualizza e accetta le offerte ricevute.',
        'permissions' => [
            'portal.client.access',
            'bookings.accept_offer',
            'catalog.view',
        ],
    ],
];

return [
    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => 'role_id',
        'permission_pivot_key' => 'permission_id',
        'model_morph_key' => 'model_id',
    ],

    'default_permissions' => $defaultPermissions,
    'default_roles' => $defaultRoles,
    'roles_matrix' => $defaultRoles,
];
