<?php

declare(strict_types=1);

return [
    'label' => 'zniżka',
    'plural_label' => 'zniżki',

    'status' => [
        'invalid' => 'Błęda',
        'finished' => 'Zakończona',
        'active' => 'Aktywna',
        'scheduled' => 'Zaplanowana',
    ],

    'table' => [
        'price' => 'Cena',
        'discount' => 'Zniżka',
        'usage' => 'Ilość',
        'active_from' => 'Od',
        'active_to' => 'Do',
        'finished_at' => 'Zakończona',
        'status' => 'Status',
    ],

    'form' => [
        'details' => 'Szczegóły',
        'price' => 'Cena',
        'discount_percentage' => 'Procent',
        'activity' => 'Aktywność',
        'active_from' => 'Od',
        'active_to' => 'Do',
        'quota' => [
            'label' => 'Kampania',
            'type' => 'Typ kampanii',
            'none' => 'Brak',
            'existing' => 'Istniejąca',
            'new' => 'Nowa',
            'active_from' => 'Od',
            'active_to' => 'Do',
            'notes' => 'Notatki',
        ],

    'actions' => [
        'finish' => [
            'label' => 'Zakończ',
            'extended_label' => 'Zakończ zniżkę',
        ],
    ],
];
