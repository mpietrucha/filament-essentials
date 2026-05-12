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
        'details' => 'Details',
        'price' => 'Price',
        'discount_percentage' => 'Percentage',
        'activity' => 'Activity',
        'active_from' => 'From',
        'active_to' => 'To',
        'quota' => [
            'label' => 'Quota',
            'none' => 'None',
            'existing' => 'Existing',
            'finish' => 'Finish',
            'empty_name' => '(empty name)',
            'new' => 'New',
            'name' => 'Name',
            'limit' => 'Limit',
            'active_from' => 'From',
            'active_to' => 'To',
            'notes' => 'Notes',
        ],
    ],

    'action' => [
        'finish' => [
            'label' => 'Finish',
        ],
        'create' => [
            'modal_label' => 'Create Discount',
        ],
        'edit' => [
            'modal_label' => 'Edit Discount',
        ],
        'view' => [
            'modal_label' => 'View Discount',
        ],
    ],
];
