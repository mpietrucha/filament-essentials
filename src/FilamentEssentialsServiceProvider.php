<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials;

use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Resources\Resource;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Livewire\Component;
use Mpietrucha\Filament\Essentials\Commands\GeneratePolicies;
use Mpietrucha\Filament\Essentials\Commands\UpgradeFilament;
use Mpietrucha\Filament\Essentials\Mixins\ActionMixin;
use Mpietrucha\Filament\Essentials\Mixins\AttachActionMixin;
use Mpietrucha\Filament\Essentials\Mixins\ColumnMixin;
use Mpietrucha\Filament\Essentials\Mixins\ComponentMixin;
use Mpietrucha\Filament\Essentials\Mixins\FieldMixin;
use Mpietrucha\Filament\Essentials\Mixins\ImageColumnMixin;
use Mpietrucha\Filament\Essentials\Mixins\IsRelatedToOperatorMixin;
use Mpietrucha\Filament\Essentials\Mixins\ResourceMixin;
use Mpietrucha\Filament\Essentials\Mixins\SelectFilterMixin;
use Mpietrucha\Filament\Essentials\Mixins\SelectMixin;
use Mpietrucha\Filament\Essentials\Mixins\TextColumnMixin;
use Mpietrucha\Filament\Essentials\Mixins\TextEntryMixin;
use Mpietrucha\Filament\Essentials\Mixins\TextInputMixin;
use Mpietrucha\Laravel\Essentials\PackageTools\Package;
use Mpietrucha\Laravel\Essentials\PackageTools\PackageServiceProvider;

class FilamentEssentialsServiceProvider extends PackageServiceProvider
{
    public function configure(Package $package): void
    {
        $package->name('filament-essentials');

        $package->hasConfigFile();
        $package->hasTranslations();
        $package->hasBladeAnonymousComponents();

        $package->hasMixins([
            Field::class => FieldMixin::class,
            Action::class => ActionMixin::class,
            Column::class => ColumnMixin::class,
            Select::class => SelectMixin::class,
            Resource::class => ResourceMixin::class,
            TextEntry::class => TextEntryMixin::class,
            Component::class => ComponentMixin::class,
            TextInput::class => TextInputMixin::class,
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

    public function packageBooted(): void
    {
        parent::packageBooted();

        $packageName = $this->package->name;

        FilamentAsset::register([
            Js::make($packageName, $this->package->basePath('../resources/dist/index.js')),
        ], sprintf('mpietrucha/%s', $packageName));
    }
}
