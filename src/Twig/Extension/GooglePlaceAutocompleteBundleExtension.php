<?php
namespace Cethyworks\GooglePlaceAutocompleteBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;

/**
 * register twig namespace, no idea why it's not automatic anymore
 */
class GooglePlaceAutocompleteBundleExtension extends AbstractExtension
{
    private $twigLoader;

    public function __construct(\Twig_Loader_Filesystem $twigLoader)
    {
        $this->twigLoader = $twigLoader;

        $bundlePath = __DIR__.'/../../Resources/assets/twig';
        $this->twigLoader->addPath($bundlePath, 'CethyworksGooglePlaceAutocompleteBundle');
    }
}
