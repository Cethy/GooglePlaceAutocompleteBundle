<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Command;

use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use PHPUnit\Framework\TestCase;

class GooglePlaceAutocompleteLibraryCommandTest extends TestCase
{
    public function testSetGoogleApiKey()
    {
        $twig = $this->getMockBuilder(\Twig_Environment::class)->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render')->with(
            '@CethyworksGooglePlaceAutocompleteBundle/Resources/assets/twig/google_place_autocomplete_library.html.twig',
            ['google_api_key' => 'foobar' ]);

        $command = new GooglePlaceAutocompleteLibraryCommand($twig, 'foobar');
        $command->setGoogleApiKey('foobar');
        $command();
    }
}
