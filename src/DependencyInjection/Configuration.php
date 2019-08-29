<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use App\Kernel;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        if (Kernel::VERSION_ID >= 40200) {
            $treeBuilder = new TreeBuilder('cethyworks_google_place_autocomplete');

            $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('google')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('api_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end() // google
            ->end();
        ;
        } else {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('cethyworks_google_place_autocomplete');

            $rootNode
                ->children()
                    ->arrayNode('google')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('api_key')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->defaultValue('')
                            ->end()
                        ->end()
                    ->end() // google
                ->end();
            ;
        }

        return $treeBuilder;
    }
}
