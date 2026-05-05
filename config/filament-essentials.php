<?php

declare(strict_types=1);

use Mpietrucha\Filament\Essentials\Resources\Discounts\Enums\DiscountStatus;

return [
    'scout' => [
        'meilisearch' => [
            'options' => [
                'attributesToHighlight' => '*',
                'highlightPreTag' => '<strong>',
                'highlightPostTag' => '</strong>',
            ],
        ],
    ],

    'discounts' => [
        'status_enum' => DiscountStatus::class,
    ],
];
