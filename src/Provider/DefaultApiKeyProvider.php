<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Provider;

class DefaultApiKeyProvider implements ApiKeyProviderInterface
{
    /** @var string */
    protected $apiKey;

    /**
     * GoogleApiKeyProvider constructor.
     *
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->apiKey;
    }
}
