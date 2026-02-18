<?php

namespace Mpietrucha\Filament\Essentials;

use Illuminate\Support\HtmlString;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Str;

class Html extends HtmlString implements CreatableInterface
{
    use Creatable;

    public static function join(string ...$elements): static
    {
        return Str::none() |> Collection::create($elements)->join(...) |> static::create(...);
    }
}
