<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimpleGooglePlaceAutocompleteType extends AbstractType
{
    protected $injectorTemplate = '@CethyworksGooglePlaceAutocompleteBundle/Resources/assets/twig/simple_google_place_autocomplete_js.html.twig';

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'injector' => [ 'template' => $this->injectorTemplate ],
            'injector_google_place_autocomplete' => true
        ));
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return 'simple_google_place_autocomplete';
    }
}
