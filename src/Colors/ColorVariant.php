<?php

namespace Mpietrucha\Filament\Essentials\Colors;

use Filament\Support\Facades\FilamentColor;
use Mpietrucha\Support\Concerns\Makeable;
use Mpietrucha\Support\Exception\RuntimeException;
use Stringable;

class ColorVariant implements Stringable
{
    use Makeable;

    /**
     * @var null|array<int|string>
     */
    protected ?array $color = null;

    public function __construct(protected readonly string $name)
    {
    }

    /**
     * @param  array<mixed>  $arguments
     */
    public static function __callStatic(string $method, array $arguments): static
    {
        return static::make($method);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        $this->get();

        return $this->name();
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<int|string>
     */
    public function get(): array
    {
        if ($color = $this->color) {
            return $color;
        }

        $color = FilamentColor::getColor($name = $this->name());

        return $this->color = $color ?? RuntimeException::throw('Variant `%s` is not registered as Filament color', $name);
    }
}
