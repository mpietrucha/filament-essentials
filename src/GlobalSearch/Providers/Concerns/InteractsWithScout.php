<?php

namespace Mpietrucha\Filament\Essentials\GlobalSearch\Providers\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Mpietrucha\Support\Concerns\Compatible;
use Mpietrucha\Support\Instance;

/**
 * @phpstan-type SearchBuilder Builder<Model>
 * @phpstan-type ScoutModel class-string<Model>
 * @phpstan-type ScoutKey int|string
 * @phpstan-type ScoutKeyName string
 */
trait InteractsWithScout
{
    use Compatible;

    public static function compatible(string $model): bool
    {
        return Instance::traits($model)->contains(Searchable::class);
    }

    /**
     * @param  ScoutModel  $model
     * @return SearchBuilder
     */
    protected static function getScoutSearchBuilder(string $model, string $query, ?Closure $callback = null): Builder
    {
        /** @var SearchBuilder */
        return $model::search($query, $callback); /** @phpstan-ignore staticMethod.notFound */
    }

    /**
     * @param  Model|ScoutModel  $modelOrRecord
     */
    protected static function getScoutKey(Model|string $modelOrRecord): int|string
    {
        /** @var ScoutKey */
        return static::getScoutRecord($modelOrRecord)->getScoutKey(); /** @phpstan-ignore method.notFound */
    }

    /**
     * @param  Model|ScoutModel  $modelOrRecord
     */
    protected static function getScoutKeyName(Model|string $modelOrRecord): string
    {
        /** @var ScoutKeyName */
        return static::getScoutRecord($modelOrRecord)->getScoutKeyName(); /** @phpstan-ignore method.notFound */
    }

    /**
     * @param  Model|ScoutModel  $modelOrRecord
     */
    protected static function getScoutRecord(Model|string $modelOrRecord): Model
    {
        /** @var Model $record */
        $record = is_string($modelOrRecord) ? resolve($modelOrRecord) : $modelOrRecord;

        return $record;
    }
}
