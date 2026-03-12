<?php

return [
    'trashed_constraint' => [
        'label' => 'Deleted',
        'operators' => [
            'with_trashed_operator' => [
                'label' => 'Includes deleted',
                'summary' => 'Deleted: includes deleted',
            ],
            'only_trashed_operator' => [
                'label' => 'Only deleted',
                'summary' => 'Deleted: only deleted',
            ],
        ],
    ],
];
