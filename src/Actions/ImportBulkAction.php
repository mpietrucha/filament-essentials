<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;
use League\Csv\Writer;
use Livewire\Component as LivewireComponent;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mpietrucha\Support\Exception\RuntimeException;
use Mpietrucha\Support\Filesystem;
use SplTempFileObject;

class ImportBulkAction extends ImportAction
{
    #[\Override]
    public function getSchema(Schema $schema): ?Schema
    {
        $schema = parent::getSchema($schema);

        if (! $schema instanceof Schema) {
            return null;
        }

        $components = $schema->getComponents(withHidden: true);

        $this->applyActionConfiguration();

        /** @phpstan-ignore argument.type */
        Arr::first($components) |> $this->applyUploadConfiguration(...);
        /** @phpstan-ignore argument.type */
        Arr::last($components) |> $this->applyFieldsetConfiguration(...);

        return $schema;
    }

    protected function applyActionConfiguration(): void
    {
        $handler = $this->getActionFunction();

        if (! $handler instanceof Closure) {
            return;
        }

        $this->action(function (self $action, array $data) use ($handler): void {
            $property = static::property();

            /** @phpstan-ignore argument.type */
            $file = Arr::get($data, $property) |> $this->merge(...);

            $data = Arr::set($data, $property, $file);

            $handler($action, $data);
        });
    }

    /**
     * @param  list<TemporaryUploadedFile>  $files
     */
    protected function merge(array $files): TemporaryUploadedFile
    {
        $files = collect($files);

        if ($files->containsOneItem()) {
            return $files->firstOrFail();
        }

        $writer = Writer::from(new SplTempFileObject);

        $headers = true;

        while ($files->isNotEmpty()) {
            $stream = $files->first() |> $this->getUploadedFileStream(...);

            if ($stream === false) {
                continue;
            }

            $response = $files->shift();

            $reader = Reader::createFromStream($stream);

            $this->synchronizeCsvDelimiter($reader);
            $this->synchronizeHeaderOffset($reader);

            if ($headers) {
                $reader->getDelimiter() |> $writer->setDelimiter(...);

                $reader->getHeader() |> $writer->insertOne(...);

                $headers = false;
            }

            $records = $reader->getRecords();

            $writer->insertOne(...) |> LazyCollection::make($records)->each(...);
        }

        $response ?? RuntimeException::throw('Unable to merge CSV files');

        $file = $response->getRealPath();

        Filesystem::put($file, $writer);

        return $response;
    }

    protected function applyUploadConfiguration(FileUpload $fileUpload): void
    {
        $fileUpload->multiple();

        __('filament-essentials::filament.import.bulk_action.placeholder') |> $fileUpload->placeholder(...);

        /** @phpstan-ignore-next-line property.notFound */
        $handler = invade($fileUpload)->afterStateUpdated |> Arr::first(...);

        $fileUpload->afterStateUpdated(null);

        $fileUpload->afterStateUpdated(static function (FileUpload $fileUpload, LivewireComponent $livewire, Set $set, array $state) use ($handler): void {
            if ($state === []) {
                return;
            }

            value($handler, $fileUpload, $livewire, $set, Arr::first($state));
        });
    }

    protected function applyFieldsetConfiguration(Fieldset $fieldset): void
    {
        /** @phpstan-ignore-next-line property.notFound */
        $schema = invade($fieldset)->childComponents |> Arr::first(...);

        if (! $schema instanceof Closure) {
            return;
        }

        $property = static::property();

        $fieldset->schema(fn (Get $get) => new class($get, $property) extends Get
        {
            public function __construct(Get $get, protected string $property)
            {
                /** @phpstan-ignore-next-line property.notFound */
                invade($get)->component |> parent::__construct(...);
            }

            public function __invoke(Component|string $property = '', bool $absolute = false): mixed
            {
                $value = parent::__invoke($property, $absolute);

                /** @var array<mixed> $value */
                return $property === $this->property ? Arr::first($value) : $value;
            }
        } |> $schema);

        $fieldset->visible(static function (Get $get) use ($property): bool {
            return $get($property) !== [];
        });
    }

    protected static function property(): string
    {
        return 'file';
    }
}
