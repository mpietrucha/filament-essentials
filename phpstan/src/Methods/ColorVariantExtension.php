<?php

namespace Mpietrucha\PHPStan\Methods;

use Mpietrucha\Filament\Essentials\Colors\ColorVariant;
use Mpietrucha\PHPStan\Reflection\ColorVariantReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use Throwable;

final class ColorVariantExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $reflection, string $method): bool
    {
        if (! $reflection->is(ColorVariant::class)) {
            return false;
        }

        try {
            ColorVariant::make($method)->get();

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    public function getMethod(ClassReflection $reflection, string $method): ColorVariantReflection
    {
        return new ColorVariantReflection($reflection, $method);
    }
}
