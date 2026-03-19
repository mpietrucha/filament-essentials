<?php

namespace Mpietrucha\Filament\Essentials\Resources\Concerns;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-require-extends Resource
 */
trait HasNumberedTitle
{
    public static function getRecordTitleAttribute(): ?string
    {
        return 'id';
    }

    public static function getRecordTitle(?Model $record): string
    {
        $label = static::getTitleCaseModelLabel();

        /** @var string $title */
        $title = parent::getRecordTitle($record);

        return sprintf('%s #%s', $label, $title);
    }
}
