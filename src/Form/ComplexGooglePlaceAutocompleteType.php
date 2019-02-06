<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form;

use Cethyworks\GooglePlaceAutocompleteBundle\Form\DataTransformer\ComplexGooglePlaceAutocompleteDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComplexGooglePlaceAutocompleteType extends AbstractType
{
    protected $injectorTemplate = '@CethyworksGooglePlaceAutocompleteBundle/complex_google_place_autocomplete_js.html.twig';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('address_components', HiddenType::class)
            ->add('autocomplete', TextType::class)
            ->addViewTransformer(new ComplexGooglePlaceAutocompleteDataTransformer())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'injector' => [ 'template' => $this->injectorTemplate ],
            'injector_google_place_autocomplete' => true
        ));
    }

    public function getBlockPrefix()
    {
        return 'complex_google_place_autocomplete';
    }
}
