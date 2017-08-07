<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Form;

use Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType;
use Cethyworks\GooglePlaceAutocompleteBundle\Test\GooglePlaceAutocompleteInjectorTypeTestCase;

class SimpleGooglePlaceAutocompleteTypeTest extends GooglePlaceAutocompleteInjectorTypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = 'foobar';
        $command = function(){};

        $this->commandFactory->expects($this->once())->method('create')->willReturn($command);
        $this->subscriber->expects($this->at(0))->method('registerCommand')->with($command);
        $this->subscriber->expects($this->at(1))->method('registerCommand')->with($this->libraryCommand);

        $form = $this->factory->create(SimpleGooglePlaceAutocompleteType::class);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('foobar', $form->getData());

        $form->createView();
    }
}
