<?php

declare(strict_types=1);

return [
    'label' => 'discount',
    'plural_label' => 'discounts',

    'status' => [
        'invalid' => 'Invalid',
        'finished' => 'Finished',
        'active' => 'Active',
        'scheduled' => 'Scheduled',
    ],

    'table' => [
        'price' => 'Price',
        'discount' => 'Discount',
        'usage' => 'Usage',
        'active_from' => 'From',
        'active_to' => 'To',
        'finished_at' => 'Finished',
        'status' => 'Status',
    ],

    'form' => [
        'price' => 'Price',
        'discount_percentage' => 'Percentage',
        'active_from' => 'Active from',
        'active_to' => 'Active to',
        'quota_type' => 'Quota',
        'quota' => [
            'none' => 'None',
            'existing' => 'Existing quota',
            'new' => 'New quota',
            'name' => 'Name',
            'limit' => 'Limit',
            'active_from' => 'Active from',
            'active_to' => 'Active to',
        ],
    ],

    'actions' => [
        'finish' => [
            'label' => 'Finish',
            'extended_label' => 'Finish discount',
        ],
    ],
];
