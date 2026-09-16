<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use BackedEnum;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;
use Mpietrucha\Laravel\Essentials\Locale;
use Mpietrucha\Laravel\Essentials\Translations\Qualifiers\KeyQualifier;
use Mpietrucha\Support\Str;

/**
 * @phpstan-require-implements EnumInterface
 */
trait InteractsWithEnum
{
    use \Mpietrucha\Support\Enums\Concerns\InteractsWithEnum;

    /**
     * @return class-string<static>
     */
    public static function options(): string
    {
        return static::class;
    }

    public function getLabel(): string
    {
        /** @var string $value */
        $value = match (true) {
            $this instanceof BackedEnum => $this->value,
            default => $this->name
        };

        if ($prefix = static::getLabelTranslationPrefix()) {
            $value = Str::lower($value);

            return KeyQualifier::build($value, $prefix) |> __(...);
        }

        if (Str::upper($value) === $value) {
            return $value;
        }

        $headline = Str::headline($value);

        if (Str::startsWith(Locale::get()->code(), 'en')) {
            return $headline;
        }

        return Str::lower($headline) |> Str::ucfirst(...);
    }

    protected static function getLabelTranslationPrefix(): ?string
    {
        return null;
    }
}
