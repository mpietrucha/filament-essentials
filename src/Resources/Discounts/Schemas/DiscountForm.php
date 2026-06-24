<?php

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component as LivewireComponent;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Resources\Discounts\DiscountResource;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Enums\QuotaType;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount\Quota;
use Symfony\Component\Intl\Currencies;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return static::components() |> $schema->components(...);
    }

    /**
     * @return list<Component>
     */
    protected static function components(): array
    {
        return [
            Fieldset::make(__('filament-essentials::discounts-plugin.form.details'))
                ->columnSpanFull()
                ->schema([
                    TextInput::make('price')
                        ->label(__('filament-essentials::discounts-plugin.form.price'))
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(static function (?Discount $discount, LivewireComponent $livewire): ?float {
                            $discountable = static::resolveDiscountable($discount, $livewire);

                            /** @phpstan-ignore-next-line */
                            return $discountable?->getPrice()?->getAmount()->toFloat();
                        })
                        ->requiredWithout('discount_percentage')
                        ->prefix(static function (?Discount $discount, LivewireComponent $livewire): ?string {
                            $discountable = static::resolveDiscountable($discount, $livewire);

                            /** @phpstan-ignore-next-line */
                            $currency = $discountable?->getPrice()->getCurrency()->getCurrencyCode();

                            if (! is_string($currency)) {
                                return null;
                            }

                            return Currencies::getSymbol($currency);
                        }),

                    TextInput::make('discount_percentage')
                        ->label(__('filament-essentials::discounts-plugin.form.discount_percentage'))
                        ->integer()
                        ->minValue(0)
                        ->maxValue(100)
                        ->requiredWithout('price')
                        ->suffix('%'),
                ]),

            Fieldset::make(__('filament-essentials::discounts-plugin.form.activity'))
                ->columnSpanFull()
                ->schema([
                    DatePicker::make('active_from')
                        ->label(__('filament-essentials::discounts-plugin.form.active_from')),

                    DatePicker::make('active_to')
                        ->label(__('filament-essentials::discounts-plugin.form.active_to'))
                        ->afterOrEqual('active_from'),
                ]),

            Fieldset::make(__('filament-essentials::discounts-plugin.form.quota.label'))
                ->columnSpanFull()
                ->schema([
                    ToggleButtons::make('quota_type')
                        ->hiddenLabel()
                        ->options(QuotaType::class)
                        ->inline()
                        ->afterStateHydrated(static function (ToggleButtons $component, ?Discount $discount): void {
                            match (true) {
                                $discount?->quota === null => QuotaType::None,
                                default => QuotaType::Existing,
                            } |> $component->state(...);
                        })
                        ->afterStateUpdated(static function (QuotaType $state, Set $set, Get $get): void {
                            if ($state === QuotaType::New) {
                                static::hydrateQuota($set);

                                return;
                            }

                            static::hydrateQuota($set, $get('quota_id'));
                        })
                        ->live()
                        ->dehydrated(false)
                        ->columnSpanFull(),

                    Select::make('quota_id')
                        ->label(__('filament-essentials::discounts-plugin.form.quota.label'))
                        ->searchable()
                        ->preload()
                        ->hintActions([
                            Action::make('finish_quota')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.finish'))
                                ->icon(Heroicon::XMark)
                                ->color('danger')
                                ->requiresConfirmation()
                                ->cancelParentActions()
                                ->visible(static fn (Get $get): bool => $get('quota_id') |> filled(...))
                                ->action(static function (Get $get, LivewireComponent $livewire): void {
                                    /** @var null|Quota $quota */
                                    $quota = $get('quota_id') |> Quota::getModel()::query()->find(...);

                                    $quota?->finish()->save();

                                    $livewire->js('$wire.$refresh()');
                                }),
                        ])
                        ->relationship('quota', 'name', static function (Builder $builder): void {
                            /** @var Builder<Quota> $builder */
                            $builder->active();
                            $builder->whereNotNull('name');

                            $builder->withCount(['discounts' => static function (Builder $builder): void {
                                /** @var Builder<Discount> $builder */
                                $builder->active($withQuota = false);
                            }]);
                        })
                        ->getSelectedRecordUsing(static function (string $state): ?Quota {
                            return Quota::getModel()::query()->find($state);
                        })
                        ->live()
                        ->afterStateUpdated(static function (?string $state, Set $set): void {
                            static::hydrateQuota($set, $state);
                        })
                        ->allowHtml()
                        ->getOptionLabelFromRecordUsing(static function (Quota $quota): HtmlString|string {
                            $name = $quota->name;

                            if ($name === null) {
                                return __('filament-essentials::discounts-plugin.form.quota.empty_name');
                            }

                            $discounts = $quota->discounts_count;

                            if ($discounts === 0) {
                                return $name;
                            }

                            $badge = Blade::renderBadge((string) $discounts, [
                                'class' => 'ml-1',
                            ]);

                            return new HtmlString(sprintf('%s %s', $name, $badge));
                        })
                        ->visible(static fn (Get $get): bool => $get('quota_type') === QuotaType::Existing)
                        ->required()
                        ->columnSpanFull(),

                    Group::make()
                        ->relationship('quota')
                        ->columns(2)
                        ->columnSpanFull()
                        ->visible(static fn (Get $get): bool => $get('quota_type') !== QuotaType::None)
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.name')),

                            TextInput::make('limit')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.limit'))
                                ->integer()
                                ->minValue(1)
                                ->prefix(static function (?Quota $record): ?string {
                                    $used = $record?->limit_used;

                                    if ($used === null) {
                                        return null;
                                    }

                                    return sprintf('%s /', $used);
                                })
                                ->hintAction(DiscountResource::getUseQuotaAction()),

                            DatePicker::make('active_from')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_from')),

                            DatePicker::make('active_to')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.active_to'))
                                ->afterOrEqual('active_from'),

                            Textarea::make('notes')
                                ->label(__('filament-essentials::discounts-plugin.form.quota.notes'))
                                ->columnSpanFull(),
                        ]),
                ]),
        ];
    }

    protected static function hydrateQuota(Set $set, mixed $state = null): void
    {
        /** @var null|Quota $quota */
        $quota = $state === null ? null : Quota::getModel()::query()->find($state);

        $set('quota.name', $quota?->name);
        $set('quota.limit', $quota?->limit);
        $set('quota.notes', $quota?->notes);
        $set('quota.active_to', $quota?->active_to);
        $set('quota.active_from', $quota?->active_from);
    }

    protected static function resolveDiscountable(?Discount $discount, LivewireComponent $livewire): ?Model
    {
        $discountable = $discount?->discountable;

        if ($discountable instanceof Model) {
            return $discountable;
        }

        /** @phpstan-ignore method.notFound */
        $mountedAction = $livewire->getMountedAction();

        if ($mountedAction instanceof BulkAction) {
            return null;
        }

        /** @phpstan-ignore method.nonObject */
        $record = $mountedAction->getParentRecord() ?? $livewire->getFilamentRecord();

        /** @phpstan-ignore property.nonObject, return.type, nullsafe.neverNull */
        return $record?->discountable ?? $record;
    }
}
