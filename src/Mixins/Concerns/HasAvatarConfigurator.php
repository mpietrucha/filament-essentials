<?php

namespace Mpietrucha\Filament\Essentials\Mixins\Concerns;

use Closure;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Record;

/**
 * @internal
 */
trait HasAvatarConfigurator
{
    /**
     * @param  Closure(): (null|string)  $title
     */
    public static function getAvatarConfigurator(?string $attribute, Closure $title): Closure
    {
        return Record::pipe(static function (Record $record) use ($attribute, $title): string {
            $avatar = $record->avatar($attribute);

            $title = $record->get((string) $title());

            if (! is_string($avatar)) {
                return $title;
            }

            return Blade::renderSelectOptionWithAvatar($title, $avatar);
        });
    }
}
