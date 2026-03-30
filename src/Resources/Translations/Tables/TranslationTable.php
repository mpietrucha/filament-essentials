<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;

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
                ->label(__('filament-essentials::translation.table.group'))
                ->searchable(),

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
        return [];
    }

    /**
     * @return list<Action|ActionGroup>
     */
    protected static function toolbarActions(): array
    {
        return [];
    }
}
