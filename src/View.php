<?php

namespace Mpietrucha\Filament\Essentials;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Str;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 */
abstract class View
{
    public static function html(string ...$elements): HtmlString
    {
        $html = Str::none() |> Collection::create($elements)->join(...);

        return new HtmlString($html);
    }

    /**
     * @param  null|MixedArray  $attributes
     */
    public static function badge(string $badge, ?array $attributes = null): string
    {
        $attributes = Normalizer::array($attributes);

        return Blade::render('<x-filament::badge {{ $attributes }}>{{ $badge }}</x-filament::badge>', [
            'badge' => $badge,
            'attributes' => new ComponentAttributeBag($attributes),
        ]);
    }

    public static function textColumnBadge(string $badge): string
    {
        return static::badge(Str::sprintf('+%s', $badge), [
            'size' => 'sm',
            'class' => 'ml-2',
        ]);
    }

    public static function selectTitleWithAvatar(string $title, string $avatar): string
    {
        return Blade::render('<x-essentials::select-title-with-avatar :avatar="$avatar">{{ $title }}</x-essentials::select-title-with-avatar>', [
            'title' => $title,
            'avatar' => $avatar,
        ]);
    }
}
