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

    'actions' => [
        'finish' => [
            'label' => 'Finish',
            'extended_label' => 'Finish discount',
        ],
    ],
];
