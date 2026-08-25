<?php

namespace Mpietrucha\PHPStan\Methods;

use Mpietrucha\Filament\Essentials\Colors\ColorVariant;
use Mpietrucha\PHPStan\Reflection\ColorVariantReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use Throwable;

final class ColorVariantExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $method): bool
    {
        if (! $classReflection->is(ColorVariant::class)) {
            return false;
        }

        try {
            ColorVariant::make($method)->get();

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    public function getMethod(ClassReflection $classReflection, string $method): ColorVariantReflection
    {
        return new ColorVariantReflection($classReflection, $method);
    }
}
