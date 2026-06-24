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
        'usage' => 'Ilość',
        'active_from' => 'Od',
        'active_to' => 'Do',
        'finished_at' => 'Zakończona',
        'status' => 'Status',
    ],

    'infolist' => [
        'details' => 'Szczegóły',
        'price' => 'Cena',
        'discount_percentage' => 'Procent',
        'status' => 'Status',
        'active_from' => 'Od',
        'active_to' => 'Do',
        'finished_at' => 'Zakończona',
        'quota' => [
            'label' => 'Pakiet',
            'name' => 'Nazwa',
            'usage' => 'Ilość',
            'active_from' => 'Od',
            'active_to' => 'Do',
            'notes' => 'Notatki',
        ],
    ],

    'form' => [
        'discount' => 'Zniżka',
        'price' => 'Cena',
        'discount_percentage' => 'Procent',
        'activity' => 'Aktywność',
        'active_from' => 'Od',
        'active_to' => 'Do',
        'quota' => [
            'label' => 'Pakiet',
            'none' => 'Brak',
            'existing' => 'Istniejący',
            'finish' => 'Zakończ',
            'empty_name' => '(brak nazwy)',
            'new' => 'Nowy',
            'name' => 'Nazwa',
            'limit' => 'Limit',
            'active_from' => 'Od',
            'active_to' => 'Do',
            'notes' => 'Notatki',
        ],
    ],

    'action' => [
        'finish' => [
            'label' => 'Zakończ',
        ],
        'bulk_finish' => [
            'label' => 'Zakończ zniżki',
        ],
        'create' => [
            'modal_label' => 'Utwórz Zniżkę',
        ],
        'bulk_create' => [
            'modal_label' => 'Utwórz Zniżki',
        ],
        'edit' => [
            'modal_label' => 'Edytuj Zniżkę',
        ],
        'view' => [
            'modal_label' => 'Podgląd Zniżki',
        ],
        'increment_quota_usage' => [
            'label' => 'Zwiększ użycie',
        ],
    ],
];
