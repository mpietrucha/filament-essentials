<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Resources\Translations\TranslationResource;
use Mpietrucha\Laravel\Essentials\Locale;
use Spatie\TranslationLoader\LanguageLine;

class TranslationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns(static::columns())
            ->filters(static::filters())
            ->recordActions(static::recordActions())
            ->toolbarActions(static::toolbarActions());
    }

    /**
     * @return list<Column>
     */
    protected static function columns(): array
    {
        return [
            TextColumn::make('group')
                ->label(__('filament-essentials::translation.table.group'))
                ->searchable(),

            TextColumn::make('key')
                ->label(__('filament-essentials::translation.table.key'))
                ->searchable(),

            TextColumn::make('text')
                ->label(__('filament-essentials::translation.table.text'))
                ->state(static function (LanguageLine $languageLine): Collection {
                    /** @phpstan-ignore property.notFound */
                    $text = $languageLine->text;

                    /** @var array<string, string> $text */
                    return collect($text)->map(static function (string $text, string $locale): HtmlString {
                        /** @phpstan-ignore-next-line staticMethod.notFound */
                        $locale = Locale::enum()::from($locale)->code() |> Blade::renderPrefixBadge(...);

                        return new HtmlString(sprintf('%s%s', $locale, $text));
                    });
                })
                ->searchable()
                ->listWithLineBreaks(),

            TextColumn::make('created_at')
                ->label(__('filament-essentials::translation.table.created_at'))
                ->dateTime()
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault(),

            TextColumn::make('updated_at')
                ->label(__('filament-essentials::translation.table.updated_at'))
                ->dateTime()
                ->sortable()
                ->toggleable()
                ->toggledHiddenByDefault(),
        ];
    }

    /**
     * @return list<BaseFilter>
     */
    protected static function filters(): array
    {
        return [];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function recordActions(): array
    {
        return [
            TranslationResource::getViewAction(),
            TranslationResource::getEditAction(),
            ActionGroup::make([
                DeleteAction::make(),
                RestoreAction::make(),
            ]),
        ];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function toolbarActions(): array
    {
        return [];
    }
}
