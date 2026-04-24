<?php

declare(strict_types=1);

return [
    'export' => [
        'completed' => '{0} Eksport :name został zakończony, ale nie wyeksportowano żadnych rekordów.|{1} Eksport :name został zakończony, wyeksportowano :count rekord.|[2,4] Eksport :name został zakończony, wyeksportowano :count rekordy.|[5,*] Eksport :name został zakończony, wyeksportowano :count rekordów.',
        'failed' => '{1} Nie udało się wyeksportować :count rekordu.|[2,4] Nie udało się wyeksportować :count rekordów.|[5,*] Nie udało się wyeksportować :count rekordów.',
    ],

    'import' => [
        'completed' => '{0} Import :name został zakończony, ale nie zaimportowano żadnych rekordów.|{1} Import :name został zakończony, zaimportowano :count rekord.|[2,4] Import :name został zakończony, zaimportowano :count rekordy.|[5,*] Import :name został zakończony, zaimportowano :count rekordów.',
        'failed' => '{1} Nie udało się zaimportować :count rekordu.|[2,4] Nie udało się zaimportować :count rekordów.|[5,*] Nie udało się zaimportować :count rekordów.',
        'bulk_action' => [
            'placeholder' => 'Prześlij plik CSV',
        ],
    ],

    'relation_manager' => [
        'modal_label' => ':Records - :record',
    ],
];
