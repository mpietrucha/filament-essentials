<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector;
use RectorFilament\Rector\MethodCall\FilamentUtilityInjectionTypeRector;
use RectorFilament\Rector\MethodCall\LivewireComponentParamNameRector;
use RectorFilament\Rector\MethodCall\ModelToRecordClosureParamRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        'src',
        'analyze',
        'resources/lang',
    ])
    ->withSkip([
        RemoveDeadStmtRector::class,
        RemoveNonExistingVarAnnotationRector::class,
        RemoveUselessParamTagRector::class,
        ClosureToArrowFunctionRector::class,
        RenameParamToMatchTypeRector::class => [
            'src/Record/Context.php',
            'src/Resources/Concerns/TitlesRecordById.php',
            'src/Resources/Concerns/InteractsWithActions.php',
            'src/Mixins/TextColumnMixin.php',
            'src/Mixins/SelectFilterMixin.php',
            'src/RelationManagers/Concerns/InteractsWithActions.php',
            'src/Resources/Translations/Schemas/TranslationForm.php',
            'src/Actions/ImportBulkAction.php',
        ],
        RemoveUselessVarTagRector::class => [
            'src/Mixins/TextColumnMixin.php',
        ],
        AddOverrideAttributeToOverriddenMethodsRector::class => [
            'src/Resources/Translations',
        ],
        AddOverrideAttributeToOverriddenPropertiesRector::class => [
            'src/Resources/Translations',
        ],
    ])
    ->withRules([
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,
        ModelToRecordClosureParamRector::class,
        LivewireComponentParamNameRector::class,
        FilamentUtilityInjectionTypeRector::class,
    ])
    ->withSets([
        LaravelSetList::LARAVEL_130,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        carbon: true
    )
    ->withPhpSets(php85: true)
    ->withBootstrapFiles([
        'vendor/mpietrucha/laravel-essentials/phpstan/bootstrap.php',
    ])
    ->withPhpstanConfigs([
        'phpstan.neon',
    ]);
