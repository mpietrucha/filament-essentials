<?php

namespace Mpietrucha\Filament\Essentials\Resources\Discounts;

use BackedEnum;
use BezhanSalleh\PluginEssentials\Concerns\Resource\HasLabels;
use BezhanSalleh\PluginEssentials\Concerns\Resource\HasNavigation;
use Filament\Actions\Action;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource as FilamentResource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Actions\CreateAction;
use Mpietrucha\Filament\Essentials\Plugins\DiscountsPlugin;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Actions\FinishDiscountAction;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Pages\ManageDiscounts;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas\DiscountForm;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas\DiscountInfolist;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Tables\DiscountsTable;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Support\Enum;

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

    public static function configureEditAction(Action $action, ?string $relation = null): Action
    {
        parent::configureEditAction($action, $relation);

        $action->hidden(static function (Discount $discount): bool {
            return $discount->isFinished();
        });

        return $action;
    }

    public static function configureCreateAction(Action $action, ?string $relation = null): Action
    {
        parent::configureCreateAction($action, $relation);

        if ($action instanceof CreateAction) {
            $action->createAnother(false);
        }

        $action->hidden(static function (Component $livewire): bool {
            /** @phpstan-ignore property.notFound */
            $discount = $livewire->getFilamentRecord()?->discount;

            if (! $discount instanceof Discount) {
                return false;
            }

            return $discount->isActive();
        });

        static::configureActionModalHeading($action);

        return $action;
    }

    public static function configureAction(Action $action, ?string $relation = null): Action
    {
        parent::configureAction($action, $relation);

        $action->modalWidth(Width::Large);

        static::configureActionModalHeading($action);

        return $action;
    }

    public static function configureActionModalHeading(Action $action): Action
    {
        $operation = $action->getOperation()->value;

        __(sprintf('filament-essentials::discounts-plugin.action.%s.modal_label', $operation)) |> $action->modalHeading(...);

        return $action;
    }

    public static function getRecordStatus(Discount $discount): BackedEnum
    {
        $status = $discount->status;

        $enum = config()->string('filament-essentials.discounts.status_enum') |> Enum::backed(...);

        return $enum::from($status);
    }

    protected static function getEssentialsPlugin(): DiscountsPlugin
    {
        return DiscountsPlugin::get();
    }
}
