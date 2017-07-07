<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Dummy;

use Cethyworks\ContentInjectorBundle\CethyworksContentInjectorBundle;
use Cethyworks\GooglePlaceAutocompleteBundle\CethyworksGooglePlaceAutocompleteBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Created by PhpStorm.
 * User: cethy
 * Date: 07/07/2017
 * Time: 10:31
 */
class DummyKernel extends Kernel
{

    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),

            new CethyworksContentInjectorBundle(),
            new CethyworksGooglePlaceAutocompleteBundle()
        ];
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/../var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/../var/logs';
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__. '/config.yml');
    }
}
