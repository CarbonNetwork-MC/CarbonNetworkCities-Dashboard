<?php

return [
    'starter' => [
        'account' => [
            'type' => 'checking',
            'currency' => 'EUR',
            'starting_balance' => '5000.00',
        ],
        'grants' => [
            'credit_card' => [
                'grant_type' => 'item',
                'item_internal_id' => 'carbonnetwork:credit_card',
                'quantity' => 1,
                'idempotency_key' => 'onboarding:credit_card:v1',
            ],
            'food' => [
                'grant_type' => 'item',
                'item_internal_id' => 'carbonnetwork:starter_food',
                'quantity' => 32,
                'idempotency_key' => 'onboarding:starter_food:v1',
            ],
        ],
    ],
];
