<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Console\Commands;

use Filament\Tables\Columns\TextColumn;
use Mpietrucha\Filament\Essentials\Actions\TableColumnAction;
use Mpietrucha\Laravel\Essentials\Console\Command;
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
        $this->handleSelectComponent();
        $this->handleSelectFilter();
        $this->handleIsRelatedToOperator();
        $this->handleInteractsWithActions();

        $this->handleAdvancedTablesDateFilter();
        $this->handleAdvancedTablesTextFilter();
        $this->handleAdvancedTablesNumericFilter();

        $this->info('Filament upgraded successfully.');
    }

    protected function handleSelectComponent(): void
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

    protected function handleSelectFilter(): void
    {
        $this->replace(
            base_path('vendor/filament/tables/src/Filters/SelectFilter.php'),
            'return $field;',
            'return $field->allowHtml();',
        );
    }

    protected function handleIsRelatedToOperator(): void
    {
        $this->replace(
            base_path('vendor/filament/query-builder/src/Constraints/RelationshipConstraint/Operators/IsRelatedToOperator.php'),
            'return [$field];',
            'return [$field->allowHtml()];',
        );
    }

    protected function handleInteractsWithActions(): void
    {
        $interactsWithActions = base_path('vendor/filament/actions/src/Concerns/InteractsWithActions.php');

        $tableColumnAction = ClassNamespace::canonicalize(TableColumnAction::class);

        $this->replace(
            $interactsWithActions,
            $from = '$resolvedAction->getRootGroup()?->record($record) ?? $resolvedAction->record($record)',
            sprintf('$resolvedAction = %s::resolve(%s)', $tableColumnAction, $from),
        );

        $textColumn = ClassNamespace::canonicalize(TextColumn::class);

        $this->replace(
            $interactsWithActions,
            $from = '$column->getAction() === $resolvedAction',
            sprintf('%s || ($column instanceof %s && $column->getIconAction() === $resolvedAction)', $from, $textColumn),
        );
    }

    protected function handleAdvancedTablesDateFilter(): void
    {
        $dateFilter = base_path('vendor/archilex/filament-filter-sets/src/Filters/DateFilter.php');

        $this->replace(
            $dateFilter,
            $from = "Select::make('unit')",
            sprintf('%s->searchable()', $from),
        );

        $this->replace(
            $dateFilter,
            $from = "Select::make('operator')",
            sprintf('%s->searchable()', $from),
        );
    }

    protected function handleAdvancedTablesTextFilter(): void
    {
        $this->replace(
            base_path('vendor/archilex/filament-filter-sets/src/Filters/TextFilter.php'),
            $from = "Select::make('operator')",
            sprintf('%s->searchable()', $from),
        );
    }

    protected function handleAdvancedTablesNumericFilter(): void
    {
        $this->replace(
            base_path('vendor/archilex/filament-filter-sets/src/Filters/NumericFilter.php'),
            $from = "Select::make('operator')",
            sprintf('%s->searchable()', $from),
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
