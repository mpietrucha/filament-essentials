<?php

namespace Mpietrucha\Filament\Essentials\Actions;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use League\Csv\Reader;
use League\Csv\Writer;
use Livewire\Component as Livewire;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mpietrucha\Filament\Essentials\Actions\Exception\ImportMergeException;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Enumerable\LazyCollection;
use Mpietrucha\Utility\Filesystem;
use Mpietrucha\Utility\Type;
use Mpietrucha\Utility\Value;
use SplTempFileObject;

class ImportBulkAction extends ImportAction
{
    public function getSchema(Schema $schema): ?Schema
    {
        $schema = parent::getSchema($schema);

        if (Type::null($schema)) {
            return null;
        }

        $components = $schema->getComponents(withHidden: true);

        $this->applyActionConfiguration();

        Arr::first($components) |> $this->applyUploadConfiguration(...);
        Arr::last($components) |> $this->applyFieldsetConfiguration(...);

        return $schema;
    }

    protected function applyActionConfiguration(): void
    {
        $handler = $this->getActionFunction();

        if (Type::null($handler)) {
            return;
        }

        $this->action(function (self $action, array $data) use ($handler) {
            $property = static::property();

            $file = Arr::get($data, $property) |> $this->merge(...);

            $data = Arr::set($data, $property, $file);

            Value::for($handler)->get($action, $data);
        });
    }

    /**
     * @param  list<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile>  $files
     */
    protected function merge(array $files): TemporaryUploadedFile
    {
        $files = Collection::create($files);

        if ($files->containsOneItem()) {
            return $files->first();
        }

        $writer = Writer::from(new SplTempFileObject);

        $headers = true;

        while ($files->isNotEmpty()) {
            $stream = $files->first() |> $this->getUploadedFileStream(...);

            if (Type::null($stream)) {
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

            $writer->insertOne(...) |> LazyCollection::create($records)->each(...);
        }

        $response ?? ImportMergeException::create()->throw();

        Filesystem::put($response->getPath(), $writer);

        return $response;
    }

    protected function applyUploadConfiguration(FileUpload $upload): void
    {
        $upload->multiple();

        __('filament-essentials::import.bulk_action.placeholder') |> $upload->placeholder(...);

        /** @phpstan-ignore-next-line property.notFound */
        $handler = invade($upload)->afterStateUpdated |> Arr::first(...);

        $upload->afterStateUpdated(null);

        $upload->afterStateUpdated(function (FileUpload $component, Livewire $livewire, Set $set, array $state) use ($handler) {
            if (Arr::isEmpty($state)) {
                return;
            }

            Value::for($handler)->get($component, $livewire, $set, Arr::first($state));
        });
    }

    protected function applyFieldsetConfiguration(Fieldset $fieldset): void
    {
        /** @phpstan-ignore-next-line property.notFound */
        $schema = invade($fieldset)->childComponents |> Arr::first(...);

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

                /** @phpstan-ignore-next-line argument.templateType */
                return $property === $this->property ? Arr::first($value) : $value;
            }
        } |> Value::for($schema)->get(...));

        $fieldset->visible(fn (Get $get) => $get($property) |> Arr::isNotEmpty(...));
    }

    protected static function property(): string
    {
        return 'file';
    }
}
