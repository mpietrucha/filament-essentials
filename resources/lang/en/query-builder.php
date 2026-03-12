<?php

return [
    'constraints' => [
        'trashed' => [
            'label' => 'Trashed',
        ],
    ],

    'operators' => [
        'trashed' => [
            'label' => [
                'inverse' => 'With trashed',
                'direct' => 'Only trashed',
            ],

            'summary' => [
                'inverse' => 'Trashed: with trashed',
                'direct' => 'Trashed: only trashed',
            ],
        ],
    ],
];
