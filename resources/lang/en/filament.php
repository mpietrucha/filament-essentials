<?php

declare(strict_types=1);

return [
    'export' => [
        'completed' => '{0} Your :name export has completed but no rows were exported.|{1} Your :name export has completed with :count row exported.|[2,*] Your :name export has completed with :count rows exported.',
        'failed' => '1 row failed to export.|:count rows failed to export.',
    ],

    'import' => [
        'completed' => '{0} Your :name import has completed but no rows were imported.|{1} Your :name import has completed with :count row imported.|[2,*] Your :name import has completed with :count rows imported.',
        'failed' => '1 row failed to import.|:count rows failed to import.',
        'bulk_action' => [
            'placeholder' => 'Upload a CSV files',
        ],
    ],

    'relation_manager' => [
        'modal_label' => ':Records - :record',
    ],
];
