<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Resources\Discounts\Actions;

use Filament\Actions\BulkAction;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Mpietrucha\Filament\Essentials\Resources\Discounts\Schemas\DiscountForm;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount;
use Mpietrucha\Laravel\Essentials\Eloquent\Models\Discount\Quota;

class CreateDiscountBulkAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        __('filament-essentials::discounts-plugin.action.bulk_create.modal_label') |> $this->label(...);

        $this->icon(Heroicon::Plus);

        $this->deselectRecordsAfterCompletion();

        $this->schema(static fn (Schema $schema): Schema => DiscountForm::configure(
            Discount::getModel() |> $schema->model(...)
        ));

        $this->action(static function (Collection $records, array $data): void {
            /** @var array<string, mixed> $data */
            $quota = static::getQuota($data);

            $records->each(static function (Model $record) use ($data, $quota): void {
                /** @phpstan-ignore property.notFound */
                if ($record->discount instanceof Discount) {
                    $record->discount->finish()->save();
                }

                $discount = Arr::only($data, [
                    'price',
                    'active_to',
                    'active_from',
                    'discount_percentage',
                /** @phpstan-ignore argument.type */
                ]) |> Discount::getModel()::query()->create(...);

                $discount->discountable()->associate(
                    /** @phpstan-ignore property.notFound */
                    $record->discountable instanceof Model ? $record->discountable : $record
                );

                if (! $quota instanceof Quota) {
                    return;
                }

                $discount->quota()->associate($quota);
            });
        });
    }

    public static function getDefaultName(): string
    {
        return 'create-discount-bulk';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected static function getQuota(array $data): ?Quota
    {
        $quotaId = Arr::get($data, 'quota_id');

        if (is_int($quotaId)) {
            return Quota::getModel()::query()->find($quotaId);
        }

        $quota = Arr::get($data, 'quota');

        if (! is_array($quota) || blank($quota)) {
            return null;
        }

        return Arr::only($quota, [
            'name',
            'notes',
            'limit',
            'active_to',
            'active_from',
        ]) |> Quota::getModel()::query()->create(...); /** @phpstan-ignore argument.type */
    }
}
