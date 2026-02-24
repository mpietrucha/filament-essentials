<?php

namespace Mpietrucha\Filament\Essentials\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-require-extends \Filament\Resources\Resource
 */
trait HasIncrementingLabel
{
    public static function getRecordTitleAttribute(): ?string
    {
        return 'id';
    }

    public static function getRecordTitle(?Model $record): string
    {
        $label = static::getTitleCaseModelLabel();

        $title = parent::getRecordTitle($record);

        return Str::sprintf('%s #%s', $label, $title);
    }
}
