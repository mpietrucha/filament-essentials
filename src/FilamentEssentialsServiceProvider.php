<?php

namespace Mpietrucha\Filament\Essentials;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Mpietrucha\Filament\Essentials\Commands\GeneratePolicies;
use Mpietrucha\Filament\Essentials\Commands\UpgradeFilament;
use Mpietrucha\Filament\Essentials\Mixins\AttachActionMixin;
use Mpietrucha\Filament\Essentials\Mixins\FieldMixin;
use Mpietrucha\Filament\Essentials\Mixins\ImageColumnMixin;
use Mpietrucha\Filament\Essentials\Mixins\IsRelatedToOperatorMixin;
use Mpietrucha\Filament\Essentials\Mixins\SelectFilterMixin;
use Mpietrucha\Filament\Essentials\Mixins\SelectMixin;
use Mpietrucha\Filament\Essentials\Mixins\TextColumnMixin;
use Mpietrucha\Laravel\Essentials\PackageTools\Package;
use Mpietrucha\Laravel\Essentials\PackageTools\PackageServiceProvider;

class FilamentEssentialsServiceProvider extends PackageServiceProvider
{
    public function configure(Package $package): void
    {
        $package->name('filament-essentials');

        $package->hasTranslations();
        $package->hasBladeAnonymousComponents();

        $package->hasMixins([
            Field::class => FieldMixin::class,
            Select::class => SelectMixin::class,
            TextColumn::class => TextColumnMixin::class,
            ImageColumn::class => ImageColumnMixin::class,
            AttachAction::class => AttachActionMixin::class,
            SelectFilter::class => SelectFilterMixin::class,
            IsRelatedToOperator::class => IsRelatedToOperatorMixin::class,
        ]);

        $package->hasCommands([
            UpgradeFilament::class,
            GeneratePolicies::class,
        ]);
    }
}
