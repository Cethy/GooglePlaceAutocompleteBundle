<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form\DataTransformer;

use Cethyworks\GooglePlaceAutocompleteBundle\Model\Place;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ComplexGooglePlaceAutocompleteDataTransformer implements DataTransformerInterface
{
    /**
     * Transforms from entity value to form view
     *
     * @param Place $place
     * @return array
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($place)
    {
        if($place === null) {
            $place = new Place('');
        }

        if(! $place instanceof Place) {
            throw new TransformationFailedException('the value given to transform must be a Place object');
        }

        return [
            'autocomplete'       => $place->getName(),
            'name'               => $place->getName(),
            'address_components' => $this->encode($place)
        ];
    }

    /**
     * Transforms from form view to entity value
     *
     * @param array $value
     * @return null|Place
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        if(! $value['name']) {
            return null;
        }
        
        $addressComponents = json_decode($value['address_components'], true);

        $placeArray = array_merge([
            'name' => $value['name'] ? $value['name'] : $value['autocomplete'],
        ], $this->decode(is_array($addressComponents) ? $addressComponents : []));

        return Place::build($placeArray);
    }


    /**
     * @param Place $place
     * @return string
     */
    protected function encode(Place $place)
    {
        $addressComponents = [];
        foreach($place->toArray() as $key => $addressComponent) {
            if($key == 'name') {continue;}
            //[{"long_name":"Footscray","short_name":"Footscray","types":["locality","political"]},
            $addressComponents[] = [
                'long_name' => $addressComponent,
                'types'     => [$key]
            ];
        }

        return json_encode($addressComponents);
    }

    /**
     * @param array $addressComponents
     * @return array
     */
    protected function decode(array $addressComponents)
    {
        $placeArray = [];
        foreach($addressComponents as $addressComponent) {
            $placeArray[$addressComponent['types'][0]] = $addressComponent['long_name'];
        }

        return $placeArray;
    }
}
