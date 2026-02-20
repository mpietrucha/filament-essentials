<?php

namespace Mpietrucha\Filament\Essentials\Commands;

use Illuminate\Console\Command;
use Mpietrucha\Laravel\Essentials\Commands\Concerns\InteractsWithLint;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Enum;
use Mpietrucha\Utility\Enums\Contracts\InteractsWithEnumInterface;
use Mpietrucha\Utility\Filesystem;
use Mpietrucha\Utility\Str;

class GenerateColors extends Command
{
    use InteractsWithLint;

    /**
     * @var string
     */
    protected $signature = 'essentials:generate-colors
                            {--input=App\Enums\Color : The Color enum class to generate CSS variables from}
                            {--output=css/colors.css : The resource file to write the generated CSS to}';

    /**
     * @var string
     */
    protected $description = 'Generate CSS color variables';

    public function handle(): int
    {
        /** @var string */
        $input = $this->option('input');

        if (Enum::incompatible($input)) {
            Str::sprintf('The [%s] enum does not exist or does not implement [%s].', $input, InteractsWithEnumInterface::class) |> $this->error(...);

            return Command::FAILURE;
        }

        $colors = $input::collection()->pipeThrough([
            fn (Collection $colors) => $this->build(...) |> $colors->map(...),
            fn (Collection $colors) => $colors->prepend('@theme {'),
            fn (Collection $colors) => $colors->push('}'),
            fn (Collection $colors) => Str::eol() |> $colors->join(...),
        ]);

        $output = $this->option('output') |> resource_path(...);

        Filesystem::put($output, $colors);

        $this->lint($output);

        Str::sprintf('Colors generated successfully in [%s]', $output) |> $this->info(...);

        return Command::SUCCESS;
    }

    protected function build(InteractsWithEnumInterface $color): string
    {
        $name = $color->key() |> Str::lower(...);

        $value = $color->value();

        return Str::sprintf('--color-%s: %s', $name, $value);
    }
}
