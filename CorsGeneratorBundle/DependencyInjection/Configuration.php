<?php

namespace CrossSymfony\CorsGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\BooleanNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('cors_generator');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for symfony/config < 4.2
            $rootNode = $treeBuilder->root('cors_generator');
        }

        $rootNode
            ->children()
                ->append($this->getAllowCredentials())
                ->append($this->getAllowOrigin())
                ->append($this->getAllowHeaders())
                ->append($this->getAllowMethods())
                ->append($this->getMaxAge())
            ->end();

        return $treeBuilder;
    }

    private function getAllowCredentials(): BooleanNodeDefinition
    {
        $node = new BooleanNodeDefinition('allow_credentials');
        $node->defaultFalse();

        return $node;
    }

    private function getAllowOrigin(): ScalarNodeDefinition
    {
        $node = new ScalarNodeDefinition('allow_origin');

        $node
            ->defaultValue('*')
            ->end()
        ;

        return $node;
    }

    private function getAllowHeaders(): ScalarNodeDefinition
    {
        $node = new ScalarNodeDefinition('allow_headers');

        $node
            ->defaultValue('Content-Type, Authorization')   
            ->end();

        return $node;
    }

    private function getAllowMethods(): ScalarNodeDefinition
    {
        $node = new ScalarNodeDefinition('allow_methods');

        $node->defaultValue('POST, GET')->end();

        return $node;
    }

    private function getMaxAge(): ScalarNodeDefinition
    {
        $node = new ScalarNodeDefinition('max_age');

        $node
            ->defaultValue(3600)
            ->validate()
                ->ifTrue(function ($v) {
                    return !is_numeric($v);
                })
                ->thenInvalid('max_age must be an integer (seconds)')
            ->end()
        ;

        return $node;
    }
}
