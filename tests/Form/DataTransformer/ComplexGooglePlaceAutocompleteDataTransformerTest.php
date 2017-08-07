<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Form\DataTransformer;

use Cethyworks\GooglePlaceAutocompleteBundle\Form\DataTransformer\ComplexGooglePlaceAutocompleteDataTransformer;
use Cethyworks\GooglePlaceAutocompleteBundle\Model\Place;
use PHPUnit\Framework\TestCase;

class ComplexGooglePlaceAutocompleteDataTransformerTest extends TestCase
{
    /**
     * @var ComplexGooglePlaceAutocompleteDataTransformer
     */
    protected $dataTransformer;

    protected function setUp()
    {
        $this->dataTransformer = new ComplexGooglePlaceAutocompleteDataTransformer();
    }

    public function dataTestTransform()
    {
        return [
            'null' => [[
                'autocomplete'       => '',
                'name'               => '',
                'address_components' => '[{"long_name":null,"types":["street_number"]},{"long_name":null,"types":["route"]},{"long_name":null,"types":["locality"]},{"long_name":null,"types":["postal_code"]},{"long_name":null,"types":["country"]}]'
            ], null],
            'empty Place' => [[
                'autocomplete'       => '',
                'name'               => '',
                'address_components' => '[{"long_name":null,"types":["street_number"]},{"long_name":null,"types":["route"]},{"long_name":null,"types":["locality"]},{"long_name":null,"types":["postal_code"]},{"long_name":null,"types":["country"]}]'
            ], new Place('')],
            'some Place' => [[
                'autocomplete'       => 'foo',
                'name'               => 'foo',
                'address_components' => '[{"long_name":"_sn","types":["street_number"]},{"long_name":"_route","types":["route"]},{"long_name":"_locality","types":["locality"]},{"long_name":"_postalCode","types":["postal_code"]},{"long_name":"_country","types":["country"]}]'
            ], new Place('foo', '_sn', '_route', '_locality', '_postalCode', '_country')],
        ];
    }

    /**
     * @dataProvider dataTestTransform
     */
    public function testTransform($expectedValue, Place $place = null)
    {
        $value = $this->dataTransformer->transform($place);

        $this->assertEquals($expectedValue, $value);
    }


    public function dataTestReverseTransform()
    {
        return [
            'null' => [null, [
                'autocomplete'       => '',
                'name'               => '',
                'address_components' => '[{"long_name":null,"types":["street_number"]},{"long_name":null,"types":["route"]},{"long_name":null,"types":["locality"]},{"long_name":null,"types":["postal_code"]},{"long_name":null,"types":["country"]}]'
            ]],
            'some Place' => [new Place('foo', '_sn', '_route', '_locality', '_postalCode', '_country'), [
                'autocomplete'       => 'foo',
                'name'               => 'foo',
                'address_components' => '[{"long_name":"_sn","types":["street_number"]},{"long_name":"_route","types":["route"]},{"long_name":"_locality","types":["locality"]},{"long_name":"_postalCode","types":["postal_code"]},{"long_name":"_country","types":["country"]}]'
            ]],
        ];
    }

    /**
     * @dataProvider dataTestReverseTransform
     */
    public function testReverseTransform($expectedPlace, $value)
    {
        $place = $this->dataTransformer->reverseTransform($value);

        $this->assertEquals($expectedPlace, $place);
    }
}
