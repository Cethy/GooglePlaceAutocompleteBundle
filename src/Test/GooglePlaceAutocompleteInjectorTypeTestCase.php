<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Test;

use Cethyworks\ContentInjectorBundle\Test\InjectorTypeTestCase;
use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use Cethyworks\GooglePlaceAutocompleteBundle\Form\Extension\GooglePlaceAutocompleteInjectorAwareTypeExtension;
use Symfony\Component\Form\FormTypeExtensionInterface;

/**
 * @codeCoverageIgnore
 */
class GooglePlaceAutocompleteInjectorTypeTestCase extends InjectorTypeTestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|GooglePlaceAutocompleteLibraryCommand */
    protected $libraryCommand;

    public function setUp()
    {
        $this->libraryCommand = $this->getMockBuilder(GooglePlaceAutocompleteLibraryCommand::class)->disableOriginalConstructor()->getMock();

        parent::setUp();
    }

    /**
     * @return FormTypeExtensionInterface[]
     */
    protected function getTypeExtensions()
    {
        $typeExtensions = parent::getTypeExtensions();
        $typeExtensions[] = new GooglePlaceAutocompleteInjectorAwareTypeExtension($this->subscriber, $this->libraryCommand);
        return $typeExtensions;
    }
}
