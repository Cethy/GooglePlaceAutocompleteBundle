<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form;

use Cethyworks\ContentInjectorBundle\Form\AbstractFormViewAwareInjectorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SimpleGooglePlaceAutocompleteType extends AbstractFormViewAwareInjectorType
{
    public function getParent()
    {
        return TextType::class;
    }
}
