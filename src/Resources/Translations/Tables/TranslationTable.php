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

            TextColumn::make('languages')
                ->label(__('filament-essentials::translation.table.languages'))
                ->state(static function (LanguageLine $languageLine) {
                    /** @phpstan-ignore property.notFound */
                    $text = $languageLine->text;

                    /** @var array<string, string> $text */
                    return collect($text)
                        ->keys()
                        ->map(Locale::enum()::from(...)) /** @phpstan-ignore staticMethod.notFound */
                        ->map
                        ->code();
                })
                ->badge()
                ->listWithLineBreaks(),

            TextColumn::make('text')
                ->label(__('filament-essentials::translation.table.text'))
                ->listWithLineBreaks()
                ->searchable()
                ->toggleable()
                ->toggledHiddenByDefault(),

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
