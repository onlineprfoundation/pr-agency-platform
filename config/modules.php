<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Marketplace (Future)
    |--------------------------------------------------------------------------
    |
    | Official modules published by Online.PR. Future: exclusive marketplace
    | for premium modules.
    |
    */

    'marketplace_url' => env('MODULE_MARKETPLACE_URL', 'https://online.pr/modules'),

    'official' => [
        'online-pr/projects' => [
            'name' => 'Project Management',
            'description' => 'Client projects with publication selection, documents, messages, and invoicing.',
            'version' => '1.0.0',
        ],
        'online-pr/invoicing' => [
            'name' => 'Stripe Invoicing',
            'description' => 'Generate invoices and collect payment via Stripe.',
            'version' => '1.0.0',
        ],
    ],

];
