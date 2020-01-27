<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\DependencyInjection;

use Cethyworks\GooglePlaceAutocompleteBundle\Provider\DefaultApiKeyProvider;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cethyworks_google_place_autocomplete');

        $rootNode
            ->children()
                ->arrayNode('google')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('api_key_provider')
                            ->defaultValue(DefaultApiKeyProvider::class)
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('api_key')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end() // google
            ->end();
        ;

        return $treeBuilder;
    }
}
