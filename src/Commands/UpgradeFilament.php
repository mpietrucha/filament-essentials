<?php

declare(strict_types=1);

namespace Mpietrucha\Filament\Essentials\Commands;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Mpietrucha\Support\ClassNamespace;
use Mpietrucha\Support\Filesystem;

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
        $this->actionBelongsToTableTrait();

        $this->info('Filament upgraded successfully.');
    }

    protected function select(): void
    {
        $indicator = 'o.className="fi-dropdown-header"';

        $this->replace(
            public_path('js/filament/forms/components/select.js'),
            sprintf('%s,o.textContent=t', $indicator),
            sprintf('%s,this.isHtmlAllowed?o.innerHTML=t:o.textContent=t', $indicator)
        );
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

    protected function actionBelongsToTableTrait(): void
    {
        $files = [
            base_path('vendor/filament/actions/src/Concerns/BelongsToTable.php'),
            base_path('vendor/mpietrucha/filament-essentials/src/Actions/PendingColumnAction.php'),
        ];

        $methodSignature = 'public function table(?Table $table): %s';

        $returnType = sprintf(
            '%s|%s',
            Action::class |> ClassNamespace::canonicalize(...),
            ActionGroup::class |> ClassNamespace::canonicalize(...),
        );

        $this->replace(
            $files,
            sprintf($methodSignature, 'static'),
            sprintf($methodSignature, $returnType),
        );
    }

    /**
     * @param  string|array<string>  $files
     */
    protected function replace(array|string $files, string $from, string $to): void
    {
        $files = Collection::wrap($files)->filter(Filesystem::exists(...));

        $files->each(static fn (string $file) => Filesystem::replaceInFile(
            $from,
            $to,
            $file,
        ));
    }
}
