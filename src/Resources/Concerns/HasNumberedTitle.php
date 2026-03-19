<?php

namespace Mpietrucha\Filament\Essentials\Resources\Concerns;

use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
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

    public static function getRecordTitle(?Model $record): ?string
    {
        $title = parent::getRecordTitle($record);

        if ($title === null) {
            return null;
        }

        if ($title instanceof Htmlable) {
            $title = $title->toHtml();
        }

        return sprintf('%s #%s', static::getTitleCaseModelLabel(), $title);
    }
}
