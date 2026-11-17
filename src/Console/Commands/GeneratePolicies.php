<?php

namespace Mpietrucha\Filament\Essentials\Console\Commands;

use BezhanSalleh\FilamentShield\Commands\GenerateCommand;
use Filament\Facades\Filament;
use Mpietrucha\Laravel\Essentials\Console\Command;
use Mpietrucha\Laravel\Essentials\Console\Commands\Concerns\InteractsWithLint;
use Mpietrucha\Support\Filesystem;
use Mpietrucha\Support\Finder;

class GeneratePolicies extends Command
{
    use InteractsWithLint;

    /**
     * @var string
     */
    #[\Override]
    protected $signature = 'essentials:generate-policies
                            {--model=App\Models\User : The User model class to use in generated policies}
                            {--panel=default : The panel ID to generate policies for}
                            {--policies=app/Policies : The directory path to scan and rewrite generated policies}';

    /**
     * @var string
     */
    #[\Override]
    protected $description = 'Generate and configure Shield policies';

    public function handle(): int
    {
        /** @var string */
        $id = $this->option('panel');

        $panel = match (true) {
            $id === 'default' => Filament::getCurrentOrDefaultPanel(),
            default => Filament::getPanel($id)
        };

        if ($panel === null) {
            sprintf('Panel [%s] does not exist.', $id) |> $this->error(...);

            return Command::FAILURE;
        }

        $panel->getId() |> $this->generate(...);

        $this->rewrite();

        $this->info('Policies generated successfully.');

        return Command::SUCCESS;
    }

    protected function generate(string $id): void
    {
        $arguments = [
            '--all' => true,
            '--panel' => $id,
            '--ignore-existing-policies' => true,
            '--option' => 'policies_and_permissions',
        ];

        $this->callSilently(GenerateCommand::class, $arguments);
    }

    protected function rewrite(): void
    {
        /** @phpstan-ignore argument.type */
        $directory = $this->option('policies') |> base_path(...);

        /** @var string */
        $model = $this->option('model');

        Finder::make()
            ->in($directory)
            ->files()
            ->get()
            ->keys()
            ->each(fn (string $policy) => $this->replace($policy, $model));

        $this->lint($directory);
    }

    protected function replace(string $policy, string $model): void
    {
        $from = [
            'use Illuminate\Foundation\Auth\User as AuthUser;',
            'AuthUser',
            '$authUser',
        ];

        $to = [
            sprintf('use %s;', $model),
            'User',
            '$user',
        ];

        Filesystem::replaceInFile($from, $to, $policy);
    }
}
