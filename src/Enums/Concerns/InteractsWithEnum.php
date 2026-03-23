<?php

namespace Mpietrucha\Filament\Essentials\Enums\Concerns;

use BackedEnum;
use Mpietrucha\Filament\Essentials\Enums\Contracts\EnumInterface;
use Mpietrucha\Laravel\Essentials\Locale;
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
        /** @var int|string $value */
        $value = match (true) {
            $this instanceof BackedEnum => $this->value,
            default => $this->name
        };

        $locale = Locale::get()->code();

        $headline = Str::headline((string) $value);

        if (Str::startsWith($locale, 'en')) {
            return $headline;
        }

        return Str::lower($headline) |> Str::ucFirst(...);
    }
}
