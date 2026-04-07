<?php

namespace Mpietrucha\Filament\Essentials\GlobalSearch\Providers;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Laravel\Scout\Builder;
use Meilisearch\Endpoints\Indexes;
use Meilisearch\Search\SearchResult;
use Mpietrucha\Filament\Essentials\GlobalSearch\Providers\Concerns\InteractsWithScout;
use Mpietrucha\Support\Exception\BadMethodCallException;

/**
 * @phpstan-import-type ScoutKey from InteractsWithScout
 *
 * @phpstan-type CurrentSearchFormattedHit array<string, string>
 * @phpstan-type CurrentSearchHitCollection Collection<ScoutKey, mixed>
 */
class MeilisearchGlobalSearchProvider extends ScoutGlobalSearchProvider
{
    /**
     * @var null|CurrentSearchFormattedHit
     */
    protected static ?array $currentSearchFormattedHit = null;

    /**
     * @var null|CurrentSearchHitCollection
     */
    protected static ?Collection $currentSearchHits = null;

    #[\Override]
    protected static function getScoutSearchBuilder(string $model, string $query, ?Closure $callback = null): Builder
    {
        $meilisearchOptions = config()->array('filament-essentisls.scout.meilisearch.options', []);

        $builder = parent::getScoutSearchBuilder(
            $model,
            $query,
            static fn (Indexes $indexes, string $query, array $options): SearchResult => $indexes->search($query, [
                ...$options,
                ...$meilisearchOptions,
            ])
        );

        return $builder->withRawResults(static function (array $result) use ($model): void {
            /** @var array<int, mixed> $hits */
            $hits = Arr::get($result, 'hits');

            static::$currentSearchHits = static::getScoutKeyName($model) |> collect($hits)->keyBy(...);
        });
    }

    #[\Override]
    protected static function getResourceResultTitle(string $resource, Model $record): Htmlable|string
    {
        $title = Arr::get(static::getCurrentSearchFormattedHit(), $resource::getRecordTitleAttribute());

        if (is_string($title)) {
            return new HtmlString($title);
        }

        return parent::getResourceResultTitle($resource, $record);
    }

    /**
     * For Scout-enabled resources, `getGlobalSearchResultDetails()` must return
     * [attributeName => label] instead of Filament's standard [label => value].
     * This allows attribute names to be resolved against Meilisearch `_formatted` hits,
     * while the label is used as the display key in the search result UI.
     */
    #[\Override]
    protected static function getResourceResultDetails(string $resource, Model $record): array
    {
        $details = parent::getResourceResultDetails($resource, $record);

        return collect($details)->mapWithKeys(static function (string $value, string $name): array {
            $attribute = Arr::get(static::getCurrentSearchFormattedHit(), $name);

            if (! is_string($attribute)) {
                $attribute = $value;
            }

            return [$value => new HtmlString($attribute)];
        })->all();
    }

    /**
     * @return CurrentSearchHitCollection
     */
    protected static function getCurrentSearchHits(): Collection
    {
        return static::$currentSearchHits ?? BadMethodCallException::throw('Current search hits collection is not initialized');
    }

    /**
     * @return CurrentSearchFormattedHit
     */
    protected static function getCurrentSearchFormattedHit(): array
    {
        return static::$currentSearchFormattedHit ?? BadMethodCallException::throw('Current search formatted hit is not initialized');
    }

    protected static function beforeSearchResult(string $resource, Model $record): void
    {
        /** @var array<string, mixed> $hit */
        $hit = static::getScoutKey($record) |> static::getCurrentSearchHits()->get(...);

        /** @var CurrentSearchFormattedHit $currentSearchFormattedHit */
        $currentSearchFormattedHit = Arr::get(
            $hit,
            '_formatted'
        );

        static::$currentSearchFormattedHit = $currentSearchFormattedHit;
    }

    protected static function afterSearchResults(): void
    {
        static::$currentSearchHits = null;
        static::$currentSearchFormattedHit = null;
    }
}
