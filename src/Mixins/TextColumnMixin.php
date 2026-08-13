<?php

namespace Mpietrucha\Filament\Essentials\Mixins;

use Closure;
use Filament\Actions\Action;
use Filament\Support\View\ComponentAttributeBag;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Mpietrucha\Filament\Essentials\Actions\TableColumnAction;
use Mpietrucha\Filament\Essentials\Blade;
use Mpietrucha\Filament\Essentials\Mixins\Concerns\InteractsWithPrice;
use Mpietrucha\Laravel\Essentials\Macro\MixinProperty;

use function Filament\Support\generate_icon_html;

/**
 * @phpstan-require-extends TextColumn
 */
trait TextColumnMixin
{
    use InteractsWithPrice;

    public function withLimitBadge(): static
    {
        return $this->state(function (Model $record): null|HtmlString|string {
            $results = $this->getRelationshipResults($record) |> collect(...);

            if ($results->isEmpty()) {
                return null;
            }

            /** @var mixed $name */
            $name = $this->getFullAttributeName($record) |> $results->value(...);

            $results->shift();

            if (! is_string($name)) {
                return null;
            }

            /** @phpstan-ignore if.alwaysFalse */
            if ($results->isEmpty()) {
                return $name;
            }

            return new HtmlString(sprintf(
                '%s%s',
                $name,
                Blade::renderSuffixBadge(sprintf('+%s', $results->count()))
            ));
        });
    }

    public function iconAction(Action $action): static
    {
        MixinProperty::set($this, 'iconAction', $action);

        $icon = (clone $this)->getIcon(...);

        return $this->icon(static function (TextColumn $textColumn, ?Model $record, mixed $state) use ($icon, $action): ?Htmlable {
            if (! $record instanceof Model) {
                return null;
            }

            $icon = $icon($state) ?? $action->getIcon();

            if ($icon === null) {
                return null;
            }

            $componentAttributeBag = new ComponentAttributeBag()->merge([
                'class' => 'cursor-pointer',
                /** @phpstan-ignore argument.type */
                'wire:click.prevent.stop' => sprintf("mountTableAction('%s', '%s')", $action->getName(), $record->getKey()),
            ], false);

            return generate_icon_html($icon, attributes: $componentAttributeBag);
        });
    }

    public function resolveIconActionUsing(Closure $resolveIconActionUsing): static
    {
        TableColumnAction::make()->resolveActionUsing($resolveIconActionUsing) |> $this->iconAction(...);

        return $this;
    }

    public function getIconAction(): ?Action
    {
        /** @var null|Action */
        return MixinProperty::get($this, 'iconAction');
    }
}
