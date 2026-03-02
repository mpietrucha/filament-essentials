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
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Mpietrucha\Utility\Arr;
use Mpietrucha\Utility\Collection;
use Mpietrucha\Utility\Filesystem\Extension;
use Mpietrucha\Utility\Str;
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

            Value::for($handler)->get($action, Arr::set($data, $property, $file));
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
            $stream = $files->shift() |> $this->getUploadedFileStream(...);

            if (Type::null($stream)) {
                continue;
            }

            $reader = Reader::createFromStream($stream);

            $this->getCsvDelimiter($reader);

            $reader->setHeaderOffset($this->getHeaderOffset() ?? 0);

            if ($headers) {
                $reader->getHeader() |> $writer->insertOne(...);

                $headers = false;
            }

            $records = $reader->getRecords();

            $writer->insertOne(...) |> Collection::create($records)->each(...);
        }

        $file = Extension::set(Str::uuid(), 'csv');

        FileUploadConfiguration::storage()->put(FileUploadConfiguration::path($file), $writer);

        return TemporaryUploadedFile::createFromLivewire($file);
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
