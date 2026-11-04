<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Commands;

use Illuminate\Console\Command;
use Mpietrucha\Filament\Essentials\Actions\TableColumnAction;
use Mpietrucha\Support\ClassNamespace;
use Mpietrucha\Support\Filesystem;
use Mpietrucha\Support\Str;

class UpgradeFilament extends Command
{
    /**
     * @var string
     */
    #[\Override]
    protected $signature = 'essentials:upgrade-filament';

    /**
     * @var string
     */
    #[\Override]
    protected $description = 'Patch Filament source to enable additional features';

    public function handle(): void
    {
        $this->select();
        $this->selectFilter();
        $this->isRelatedToOperator();
        $this->interactsWithActions();

        $this->advancedTablesDateFilter();
        $this->advancedTablesTextFilter();
        $this->advancedTablesNumericFilter();

        $this->info('Filament upgraded successfully.');
    }

    protected function select(): void
    {
        $file = public_path('js/filament/forms/components/select.js');

        $contents = Filesystem::get($file);

        if (! $contents) {
            return;
        }

        $to = preg_replace(
            '/(\w+)\.className="fi-dropdown-header",\1\.textContent=(\w+)/',
            '$1.className="fi-dropdown-header",this.isHtmlAllowed?$1.innerHTML=$2:$1.textContent=$2',
            $contents
        );

        if ($contents === $to) {
            return;
        }

        /** @var string $to */
        Filesystem::put($file, $to);
    }

    protected function selectFilter(): void
    {
        $this->replace(
            base_path('vendor/filament/tables/src/Filters/SelectFilter.php'),
            'return $field;',
            'return $field->allowHtml();',
        );
    }

    protected function isRelatedToOperator(): void
    {
        $this->replace(
            base_path('vendor/filament/query-builder/src/Constraints/RelationshipConstraint/Operators/IsRelatedToOperator.php'),
            'return [$field];',
            'return [$field->allowHtml()];',
        );
    }

    protected function interactsWithActions(): void
    {
        $indicator = '$resolvedAction->getRootGroup()?->record($record) ?? $resolvedAction->record($record)';

        $tableColumnAction = ClassNamespace::canonicalize(TableColumnAction::class);

        $this->replace(
            base_path('vendor/filament/actions/src/Concerns/InteractsWithActions.php'),
            $indicator,
            sprintf('$resolvedAction = %s::resolve(%s)', $tableColumnAction, $indicator),
        );
    }

    protected function advancedTablesDateFilter(): void
    {
        $dateFilter = base_path('vendor/archilex/filament-filter-sets/src/Filters/DateFilter.php');

        $this->replace(
            $dateFilter,
            $indicator = "Select::make('unit')",
            sprintf('%s->searchable()', $indicator),
        );

        $this->replace(
            $dateFilter,
            $indicator = "Select::make('operator')",
            sprintf('%s->searchable()', $indicator),
        );
    }

    protected function advancedTablesTextFilter(): void
    {
        $this->replace(
            base_path('vendor/archilex/filament-filter-sets/src/Filters/TextFilter.php'),
            $indicator = "Select::make('operator')",
            sprintf('%s->searchable()', $indicator),
        );
    }

    protected function advancedTablesNumericFilter(): void
    {
        $this->replace(
            base_path('vendor/archilex/filament-filter-sets/src/Filters/NumericFilter.php'),
            $indicator = "Select::make('operator')",
            sprintf('%s->searchable()', $indicator),
        );
    }

    protected function replace(string $file, string $from, string $to): void
    {
        $contents = Filesystem::get($file);

        if (Str::contains($contents, $to)) {
            return;
        }

        Filesystem::put($file, Str::replace($from, $to, $contents));
    }
}
