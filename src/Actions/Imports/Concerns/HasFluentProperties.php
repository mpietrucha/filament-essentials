<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports\Concerns;

use Filament\Actions\Imports\Importer;
use Illuminate\Support\Fluent;

/**
 * @phpstan-require-extends Importer
 *
 * @phpstan-type FluentProperty Fluent<string, mixed>
 */
trait HasFluentProperties
{
    /**
     * @var null|FluentProperty
     */
    protected ?Fluent $fluentOptions = null;

    /**
     * @var null|FluentProperty
     */
    protected ?Fluent $fluentData = null;

    /**
     * @var null|FluentProperty
     */
    protected ?Fluent $fluentOriginalData = null;

    /**
     * @return FluentProperty
     */
    public function getFluentOptions(): Fluent
    {
        if ($fluentOptions = $this->fluentOptions) {
            return $fluentOptions;
        }

        return $this->fluentOptions = $this->getOptions() |> Fluent::make(...);
    }

    /**
     * @return FluentProperty
     */
    public function getFluentData(): Fluent
    {
        if ($fluentData = $this->fluentData) {
            return $fluentData;
        }

        return $this->fluentData = $this->getData() |> Fluent::make(...);
    }

    /**
     * @return FluentProperty
     */
    public function getFluentOriginalData(): Fluent
    {
        if ($fluentOriginalData = $this->fluentOriginalData) {
            return $fluentOriginalData;
        }

        return $this->fluentOriginalData = $this->getOriginalData() |> Fluent::make(...);
    }
}
