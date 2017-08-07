<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Form;

use Cethyworks\GooglePlaceAutocompleteBundle\Form\ComplexGooglePlaceAutocompleteType;
use Cethyworks\GooglePlaceAutocompleteBundle\Model\Place;
use Cethyworks\GooglePlaceAutocompleteBundle\Test\GooglePlaceAutocompleteInjectorTypeTestCase;

class ComplexGooglePlaceAutocompleteTypeTest  extends GooglePlaceAutocompleteInjectorTypeTestCase
{
    public function dataTestSubmitValidData()
    {
        return [
            'empty' => [null, [
                'autocomplete'       => '',
                'name'               => '',
                'address_components' => '[{"long_name":null,"types":["street_number"]},{"long_name":null,"types":["route"]},{"long_name":null,"types":["locality"]},{"long_name":null,"types":["postal_code"]},{"long_name":null,"types":["country"]}]' ]],
            'some place' => [new Place('foo', '_sn', '_route', '_locality', '_postalCode', '_country'), [
                'autocomplete'       => 'foo',
                'name'               => 'foo',
                'address_components' => '[{"long_name":"_sn","types":["street_number"]},{"long_name":"_route","types":["route"]},{"long_name":"_locality","types":["locality"]},{"long_name":"_postalCode","types":["postal_code"]},{"long_name":"_country","types":["country"]}]' ]]
        ];
    }

    /**
     * @dataProvider dataTestSubmitValidData
     */
    public function testSubmitValidData($expectedResult, $formData)
    {
        $command = function(){};

        $this->commandFactory->expects($this->once())->method('create')->willReturn($command);
        $this->subscriber->expects($this->at(0))->method('registerCommand')->with($command);
        $this->subscriber->expects($this->at(1))->method('registerCommand')->with($this->libraryCommand);

        $form = $this->factory->create(ComplexGooglePlaceAutocompleteType::class);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedResult, $form->getData());

        $form->createView();
    }
}
