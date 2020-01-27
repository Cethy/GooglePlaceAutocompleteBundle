<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Command;

use Cethyworks\ContentInjectorBundle\Command\TwigCommand;
use Cethyworks\GooglePlaceAutocompleteBundle\Provider\ApiKeyProviderInterface;
use Twig_Environment;

class GooglePlaceAutocompleteLibraryCommand extends TwigCommand
{
    protected $template = '@CethyworksGooglePlaceAutocompleteBundle/google_place_autocomplete_library.html.twig';

    /**
     * GooglePlaceAutocompleteLibraryCommand constructor.
     *
     * @param Twig_Environment $twig
     * @param ApiKeyProviderInterface $apiKeyProvider
     */
    public function __construct(Twig_Environment $twig, ApiKeyProviderInterface $apiKeyProvider)
    {
        parent::__construct($twig);
        $this->setGoogleApiKey($apiKeyProvider->getGoogleApiKey());
    }

    /**
     * @param string $apiKey
     *
     * @return $this
     */
    public function setGoogleApiKey($apiKey)
    {
        $this->data['google_api_key'] = $apiKey;
        return $this;
    }
}
