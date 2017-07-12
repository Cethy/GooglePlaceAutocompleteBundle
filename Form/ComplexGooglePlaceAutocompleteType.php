<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form;

use Cethyworks\ContentInjectorBundle\Form\AbstractFormViewAwareInjectorType;
use Cethyworks\GooglePlaceAutocompleteBundle\Form\DataTransformer\ComplexGooglePlaceAutocompleteDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ComplexGooglePlaceAutocompleteType extends AbstractFormViewAwareInjectorType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('address_components', HiddenType::class)

            ->add('autocomplete', TextType::class)

            ->addViewTransformer(new ComplexGooglePlaceAutocompleteDataTransformer())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'complex_google_place_autocomplete';
    }
}
