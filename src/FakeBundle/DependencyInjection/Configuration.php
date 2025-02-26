<?php

namespace App\FakeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function __construct(
        private readonly ConfigurationBuilder $builder,
    ) {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fake');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->applyDynamicConfiguration())
            ->end()
        ;

        return $treeBuilder;
    }

    private function applyDynamicConfiguration(): NodeDefinition
    {
        $defaultOptionsTreeBuilder = new TreeBuilder('default_options');
        $defaultOptionsTreeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
        ;

        foreach ($this->builder->nameToNodeMap as $key => $node) {
            $typeTreeBuilder = new TreeBuilder($key);
            $typeTreeBuilder->getRootNode()
                ->addDefaultsIfNotSet()
            ;

            $typeTreeBuilder->getRootNode()->append($node);

            $defaultOptionsTreeBuilder->getRootNode()->append($typeTreeBuilder->getRootNode());
        }

        return $defaultOptionsTreeBuilder->getRootNode();
    }
}
