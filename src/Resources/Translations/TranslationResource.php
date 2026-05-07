<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Translations;

use BezhanSalleh\PluginEssentials\Concerns\Resource\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Resource\HasNavigation;
use Filament\Actions\Action;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource as FilamentResource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\Plugins\TranslationsPlugin;
use Mpietrucha\Filament\Essentials\Resources\Translations\Pages\ManageTranslations;
use Mpietrucha\Filament\Essentials\Resources\Translations\Schemas\TranslationForm;
use Mpietrucha\Filament\Essentials\Resources\Translations\Schemas\TranslationInfolist;
use Mpietrucha\Filament\Essentials\Resources\Translations\Tables\TranslationsTable;
use Spatie\TranslationLoader\LanguageLine;

/**
 * @extends FilamentResource<LanguageLine>
 */
class TranslationResource extends FilamentResource
{
    use HasLabels;
    use HasNavigation;

    #[\Override]
    protected static ?string $model = LanguageLine::class;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return TranslationForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return TranslationInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return TranslationsTable::configure($table);
    }

    /**
     * @return array<string, PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageTranslations::route('/'),
        ];
    }

    public static function configureCreateAction(Action $action, ?string $relation = null): Action
    {
        static::configureAction($action);

        __('filament-essentials::translations-plugin.action.create.modal_heading') |> $action->modalHeading(...);

        return $action;
    }

    public static function configureDefaultAction(Action $action, ?string $relation = null): void
    {
        $action->slideOver();

        $action->modalWidth(Width::Medium);
    }

    protected static function getEssentialsPlugin(): TranslationsPlugin
    {
        return TranslationsPlugin::get();
    }
}
