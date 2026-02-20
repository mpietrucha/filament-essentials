<?php

namespace Mpietrucha\Filament\Essentials\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;
use Mpietrucha\Laravel\Essentials\Commands\Concerns\InteractsWithLint;
use Mpietrucha\Utility\Filesystem;
use Mpietrucha\Utility\Finder;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;

class GeneratePolicies extends Command
{
    use InteractsWithLint;

    /**
     * @var string
     */
    protected $signature = 'essentials:generate-policies
                            {--model=App\Models\User : The User model class to use in generated policies}
                            {--panel=default : The panel ID to generate policies for}';

    /**
     * @var string
     */
    protected $description = 'Generate and configure Shield policies';

    public function handle(): int
    {
        /** @var string */
        $id = $this->option('panel');

        $panel = match (true) {
            $id === 'default' => Filament::getCurrentOrDefaultPanel(),
            default => Filament::getPanel($id)
        };

        if (Type::null($panel)) {
            Str::sprintf('Panel [%s] does not exist.', $id) |> $this->error(...);

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

        $this->callSilently('shield:generate', $arguments);
    }

    protected function rewrite(): void
    {
        $directory = app_path('Policies');

        $model = $this->option('model');

        Finder::uncached()
            ->in($directory)
            ->get()
            ->keys()
            ->each(fn (string $policy) => Filesystem::replaceInFile(
                [
                    'use Illuminate\Foundation\Auth\User as AuthUser;',
                    'AuthUser',
                    '$authUser',
                ],
                [
                    Str::sprintf('use %s;', $model),
                    'User',
                    '$user',
                ],
                $policy
            ));

        $this->lint($directory);
    }
}
