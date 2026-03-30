<?php

namespace Mpietrucha\Filament\Essentials\Resources\Translations;

use BezhanSalleh\PluginEssentials\Concerns\Resource\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Resource\HasNavigation;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\PageRegistration;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Mpietrucha\Filament\Essentials\Plugins\TranslationsPlugin;
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
    use HasLabels;
    use HasNavigation;

    protected static ?string $model = LanguageLine::class;

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

    public static function applyDefaultActionConfiguration(Action $action): void
    {
        $action->slideOver();

        $action->modalWidth(Width::Medium);
    }

    public static function configureCreateAction(CreateAction $createAction): CreateAction
    {
        $createAction = parent::configureCreateAction($createAction);

        __('filament-essentials::translation.action.create.modal_heading') |> $createAction->modalHeading(...);

        return $createAction;
    }

    protected static function getEssentialsPlugin(): TranslationsPlugin
    {
        return TranslationsPlugin::get();
    }
}
