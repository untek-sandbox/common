<?php

namespace Untek\Database\Base\Infrastructure\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class DatabaseConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('database');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('migration')
                    ->children()
                        ->scalarNode('config_path')->end()
                    ->end()
                ->end() // migration
                ->arrayNode('seed')
                    ->children()
                        ->scalarNode('path')->end()
                    ->end()
                ->end() // seed
            ->end()
        ;

        return $treeBuilder;
    }
}
