<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\DependencyInjection\Compiler;

use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use Cethyworks\GooglePlaceAutocompleteBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ApiKeyProviderCompilerPass
 *
 * @package Cethyworks\GooglePlaceAutocompleteBundle\DependencyInjection
 */
class ApiKeyProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('cethyworks_google_place_autocomplete');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $commandDefinition = $container->getDefinition(GooglePlaceAutocompleteLibraryCommand::class);
        $providerDefinition = $container->getDefinition($config['google']['api_key_provider']);
        $commandDefinition->setArgument(1, $providerDefinition);
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array $configs
     *
     * @return array
     */
    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
