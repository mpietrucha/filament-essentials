<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts;

use BezhanSalleh\PluginEssentials\Concerns\Resource\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Resource\HasNavigation;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\PageRegistration;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Mpietrucha\Filament\Essentials\Plugins\DiscountsPlugin;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Actions\FinishDiscountAction;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Pages\ManageDiscounts;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas\DiscountForm;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas\DiscountInfolist;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Tables\DiscountsTable;
use Mpietrucha\Filament\Essentials\Resources\Resource as FilamentResource;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;

/**
 * @extends FilamentResource<Model>
 */
class DiscountResource extends FilamentResource
{
    use HasLabels;
    use HasNavigation;

    #[\Override]
    public static function getModel(): string
    {
        return Discount::getModel();
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return DiscountForm::configure($schema);
    }

    #[\Override]
    public static function infolist(Schema $schema): Schema
    {
        return DiscountInfolist::configure($schema);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return DiscountsTable::configure($table);
    }

    /**
     * @return array<string, PageRegistration>
     */
    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ManageDiscounts::route('/'),
        ];
    }

    public static function getFinishAction(): FinishDiscountAction
    {
        return FinishDiscountAction::make();
    }

    #[\Override]
    public static function configureEditAction(EditAction $editAction): EditAction
    {
        $editAction = parent::configureEditAction($editAction);

        return $editAction->hidden(static function (Discount $record): bool {
            return $record->isFinished();
        });
    }

    public static function applyDefaultActionConfiguration(Action $action, ?string $relation = null): void
    {
        $action->slideOver();

        $action->modalWidth(Width::Medium);
    }

    protected static function getEssentialsPlugin(): DiscountsPlugin
    {
        return DiscountsPlugin::get();
    }
}
