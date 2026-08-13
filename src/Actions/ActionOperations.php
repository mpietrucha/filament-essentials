<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Operation;
use Mpietrucha\Laravel\Essentials\Macro\Concerns\InteractsWithMixinProperty;

abstract class ActionOperations
{
    use InteractsWithMixinProperty;

    public static function get(Action $action): ?Operation
    {
        /** @var null|Operation */
        return match (true) {
            $action instanceof CreateAction => Operation::Create,
            $action instanceof EditAction => Operation::Edit,
            $action instanceof ViewAction => Operation::View,
            default => static::getMixinProperty($action),
        };
    }

    public static function set(Action $action, Operation $operation): void
    {
        static::setMixinProperty($action, $operation);
    }
}
