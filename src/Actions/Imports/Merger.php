<?php

namespace Mpietrucha\Filament\Essentials\Actions\Imports;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use League\Csv\Reader;
use League\Csv\Writer;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mpietrucha\Filament\Essentials\Actions\ImportBulkAction;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Concerns\Creatable;
use Mpietrucha\Utility\Contracts\CreatableInterface;
use Mpietrucha\Utility\Filesystem\Extension;
use Mpietrucha\Utility\Normalizer;
use Mpietrucha\Utility\Str;
use Mpietrucha\Utility\Type;
use Mpietrucha\Utility\Value;
use SplTempFileObject;

/**
 * @phpstan-import-type MixedArray from \Mpietrucha\Utility\Arr
 */
class Merger implements CreatableInterface
{
    use Creatable;

    public function __construct(protected ImportBulkAction $action, protected Closure $handler)
    {
    }

    /**
     * @param  MixedArray  $state
     */
    public function __invoke(FileUpload $component, Component $livewire, Set $set, array $state): void
    {
        $writer = Writer::createFromFileObject(new SplTempFileObject);

        $headers = true;

        $state = Collection::create($state);

        while ($state->isNotEmpty()) {
            $stream = $state->shift() |> $this->action()->getUploadedFileStream(...);

            if (Type::null($stream)) {
                continue;
            }

            $reader = Reader::createFromStream($stream);

            $reader->setHeaderOffset(0);

            $this->action()->getCsvDelimiter($reader);

            if ($headers) {
                $reader->getHeader() |> $writer->insertOne(...);

                $headers = false;
            }

            $records = $reader->getRecords();

            $writer->insertOne(...) |> Collection::create($records)->each(...);
        }

        $file = $this->save($writer);

        $this->propagate($component, $livewire, $set, $file);

        $component->multiple(false);
    }

    public static function build(ImportBulkAction $action, Closure $handler): Closure
    {
        return static::create($action, $handler) |> Closure::fromCallable(...);
    }

    public static function indicator(): string
    {
        return '_merged';
    }

    public static function pending(Get $get): bool
    {
        return static::indicator() |> $get->blank(...);
    }

    final public static function done(Get $get): bool
    {
        return static::pending($get) |> Normalizer::not(...);
    }

    protected function propagate(FileUpload $component, Component $livewire, Set $set, string $file): void
    {
        $upload = TemporaryUploadedFile::createFromLivewire($file);

        $handler = $this->handler();

        Value::for($handler)->get($component, $livewire, $set, $upload);

        $set(static::indicator(), true);
    }

    protected function save(Writer $writer): string
    {
        $file = Extension::set(Str::uuid(), 'csv');

        FileUploadConfiguration::storage()->put(FileUploadConfiguration::path($file), $writer);

        return $file;
    }

    protected function action(): ImportBulkAction
    {
        return $this->action;
    }

    protected function handler(): Closure
    {
        return $this->handler;
    }
}
