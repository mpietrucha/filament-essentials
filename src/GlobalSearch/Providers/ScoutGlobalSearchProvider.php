<?php

namespace Mpietrucha\Filament\Essentials\GlobalSearch\Providers;

use Filament\Facades\Filament;
use Filament\GlobalSearch\GlobalSearchResult;
use Filament\GlobalSearch\GlobalSearchResults;
use Filament\GlobalSearch\Providers\Contracts\GlobalSearchProvider;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mpietrucha\Filament\Essentials\GlobalSearch\Providers\Concerns\InteractsWithScout;

/**
 * @phpstan-type FilamentResource class-string<Resource>
 * @phpstan-type ResourceResultDetails array<string, string>
 *
 * @phpstan-import-type ScoutModel from InteractsWithScout
 */
class ScoutGlobalSearchProvider implements GlobalSearchProvider
{
    use InteractsWithScout;

    public function getResults(string $query): GlobalSearchResults
    {
        $globalSearchResults = GlobalSearchResults::make();

        /** @var array<FilamentResource> $resources */
        $resources = Filament::getResources();

        collect($resources)
            ->sort(static function (string $a, string $b): int {
                $a = $a::getGlobalSearchSort() ?? 0;
                $b = $b::getGlobalSearchSort() ?? 0;

                return $a <=> $b;
            })
            ->each(static function (string $resource) use ($query, $globalSearchResults): void {
                if (! $resource::canGloballySearch()) {
                    return;
                }

                $model = $resource::getModel();

                $results = match (true) {
                    static::compatible($model) => static::getResourceResults($resource, $query, $model),
                    default => $resource::getGlobalSearchResults($query),
                };

                if ($results->isEmpty()) {
                    return;
                }

                $globalSearchResults->category($resource::getPluralModelLabel(), $results);
            });

        return $globalSearchResults;
    }

    /**
     * @param  FilamentResource  $resource
     * @param  ScoutModel  $model
     * @return Collection<int, GlobalSearchResult>
     */
    protected static function getResourceResults(string $resource, string $query, string $model): Collection
    {
        $builder = static::getScoutSearchBuilder($model, $query);

        return $builder
            ->get()
            ->map(static function (Model $record) use ($resource): ?GlobalSearchResult {
                $url = $resource::getGlobalSearchResultUrl($record);

                if (blank($url)) {
                    return null;
                }

                static::beforeSearchResult($resource, $record);

                return new GlobalSearchResult(
                    title: static::getResourceResultTitle($resource, $record),
                    url: $url,
                    details: static::getResourceResultDetails($resource, $record),
                    actions: $resource::getGlobalSearchResultActions($record),
                );
            })
            ->filter()
            ->tap(static::afterSearchResults(...));
    }

    /**
     * @param  FilamentResource  $resource
     */
    protected static function getResourceResultTitle(string $resource, Model $record): Htmlable|string
    {
        return $resource::getGlobalSearchResultTitle($record);
    }

    /**
     * @param  FilamentResource  $resource
     * @return ResourceResultDetails
     */
    protected static function getResourceResultDetails(string $resource, Model $record): array
    {
        return $resource::getGlobalSearchResultDetails($record);
    }

    /**
     * @param  FilamentResource  $resource
     */
    protected static function beforeSearchResult(string $resource, Model $record): void
    {
    }

    protected static function afterSearchResults(): void
    {
    }
}
