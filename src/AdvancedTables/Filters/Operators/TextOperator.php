<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\AdvancedTables\Filters\Operators;

use Archilex\AdvancedTables\Filters\Operators\TextOperator as ArchilexTextOperator;
use Mpietrucha\Filament\Essentials\AdvancedTables\Exception\PackageException;

if (class_exists(ArchilexTextOperator::class)) {
    class TextOperator extends ArchilexTextOperator
    {
        public const string IN_LIST = 'in_list';

        public const string NOT_IN_LIST = 'not_in_list';
    }
} else {
    PackageException::missing('TextOperator');
}
