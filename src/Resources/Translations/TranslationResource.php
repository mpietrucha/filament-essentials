<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations;

use BackedEnum;
use Filament\Resources\Pages\PageRegistration;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\Resources\Resource as FilamentResource;
use Mpietrucha\Filament\Essentials\Resources\Translations\Pages\ManageTranslations;
use Mpietrucha\Filament\Essentials\Resources\Translations\Schemas\TranslationForm;
use Mpietrucha\Filament\Essentials\Resources\Translations\Schemas\TranslationInfolist;
use Mpietrucha\Filament\Essentials\Resources\Translations\Tables\TranslationTable;
use Spatie\TranslationLoader\LanguageLine;

/**
 * @extends FilamentResource<LanguageLine>
 */
class TranslationResource extends FilamentResource
{
    protected static ?string $model = LanguageLine::class;

    protected static null|BackedEnum|string $navigationIcon = Heroicon::User;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TranslationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TranslationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TranslationTable::configure($table);
    }

    /**
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ManageTranslations::route('/'),
        ];
    }
}
