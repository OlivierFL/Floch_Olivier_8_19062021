<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PHP_CS_FIXER);
    $containerConfigurator->import(SetList::PHP_CS_FIXER_RISKY);
    $containerConfigurator->import(SetList::DOCTRINE_ANNOTATIONS);
    $containerConfigurator->import(SetList::SYMFONY);
    $containerConfigurator->import(SetList::PSR_12);
    $services = $containerConfigurator->services();
    $services->set(ListSyntaxFixer::class)
        ->call('configure', [
            [
                'syntax' => 'short',
            ],
        ])
    ;
    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [
            [
                'syntax' => 'short',
            ],
        ])
    ;
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SKIP, [
        ListSyntaxFixer::class => [
            __DIR__.'/tests/Fixtures',
            __DIR__.'/var',
            __DIR__.'/vendor',
        ],
    ]);
    $parameters->set(Option::SKIP, [
        ArraySyntaxFixer::class => [
            __DIR__.'/tests/Fixtures',
            __DIR__.'/var',
            __DIR__.'/vendor',
        ],
    ]);
};
