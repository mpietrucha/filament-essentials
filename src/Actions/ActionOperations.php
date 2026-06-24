<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Operation;
use Illuminate\Support\Arr;

abstract class ActionOperations
{
    /**
     * @var array<string, Operation>
     */
    protected static array $operations = [];

    public static function flush(): void
    {
        static::$operations = [];
    }

    public static function get(Action $action): ?Operation
    {
        /** @var null|Operation */
        return match (true) {
            $action instanceof CreateAction => Operation::Create,
            $action instanceof EditAction => Operation::Edit,
            $action instanceof ViewAction => Operation::View,
            default => Arr::get(static::$operations, static::getActionIdentifier($action))
        };
    }

    public static function set(Action $action, Operation $operation): void
    {
        if (static::get($action) instanceof Operation) {
            return;
        }

        static::$operations[static::getActionIdentifier($action)] = $operation;
    }

    protected static function getActionIdentifier(Action $action): string
    {
        return spl_object_hash($action);
    }
}
