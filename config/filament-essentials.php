<?php

declare(strict_types=1);

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
];
