<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Command;

use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use Cethyworks\GooglePlaceAutocompleteBundle\Provider\DefaultApiKeyProvider;
use PHPUnit\Framework\TestCase;

class GooglePlaceAutocompleteLibraryCommandTest extends TestCase
{
    public function testSetGoogleApiKey()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with(
            '@CethyworksGooglePlaceAutocompleteBundle/google_place_autocomplete_library.html.twig',
            ['google_api_key' => 'foobar' ]);
        $provider = $this->getMockBuilder(DefaultApiKeyProvider::class)->disableOriginalConstructor()->getMock();
        $provider->expects($this->once())->method('getGoogleApiKey')->willReturn('foobar');

        $command = new GooglePlaceAutocompleteLibraryCommand($twig, $provider);
        $command->setGoogleApiKey('foobar');
        $command();
    }
}
