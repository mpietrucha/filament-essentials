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
        'active_from' => 'Aktywna od',
        'active_to' => 'Aktywna do',
        'finished_at' => 'Zakończona',
        'status' => 'Status',
    ],

    'infolist' => [
        'discount' => 'Zniżka',
        'price' => 'Cena',
        'discount_percentage' => 'Procent',
        'status' => 'Status',
        'active_from' => 'Aktywna od',
        'active_to' => 'Aktywna do',
        'finished_at' => 'Zakończona',
        'quota' => [
            'label' => 'Pakiet',
            'name' => 'Nazwa',
            'usage' => 'Ilość',
            'active_from' => 'Aktywny od',
            'active_to' => 'Aktywny do',
            'notes' => 'Notatki',
        ],
    ],

    'form' => [
        'discount' => 'Zniżka',
        'price' => 'Cena',
        'discount_percentage' => 'Procent',
        'active_from' => 'Aktywna od',
        'active_to' => 'Aktywna do',
        'quota' => [
            'label' => 'Pakiet',
            'none' => 'Brak',
            'existing' => 'Istniejący',
            'finish' => 'Zakończ',
            'empty_name' => '(brak nazwy)',
            'new' => 'Nowy',
            'name' => 'Nazwa',
            'limit' => 'Limit',
            'active_from' => 'Aktywny od',
            'active_to' => 'Aktywny do',
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
            'modal_label' => 'Utwórz zniżkę',
        ],
        'bulk_create' => [
            'modal_label' => 'Utwórz zniżki',
        ],
        'edit' => [
            'modal_label' => 'Edytuj zniżkę',
        ],
        'view' => [
            'modal_label' => 'Podgląd zniżki',
        ],
        'increment_quota_usage' => [
            'label' => 'Zwiększ użycie',
        ],
    ],
];
