<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Form\Extension;

use Cethyworks\ContentInjectorBundle\EventSubscriber\ContentInjectorSubscriber;
use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GooglePlaceAutocompleteInjectorAwareTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ContentInjectorSubscriber
     */
    protected $responseSubscriber;
    /**
     * @var GooglePlaceAutocompleteLibraryCommand
     */
    protected $libraryCommand;

    function __construct(ContentInjectorSubscriber $responseSubscriber, GooglePlaceAutocompleteLibraryCommand $libraryCommand)
    {
        $this->responseSubscriber = $responseSubscriber;
        $this->libraryCommand     = $libraryCommand;
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * Add injector options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('injector', false);

        $resolver->setNormalizer('injector', function (Options $options, $injectorOption) {
            // injector not enabled
            if(! $injectorOption) {
                return false;
            }

            if(! isset($injectorOption['google_place_autocomplete'])) {
                $injectorOption['google_place_autocomplete'] = false;
            }

            return $injectorOption;
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if($options['injector'] && $options['injector']['google_place_autocomplete']) {
            $this->registerGooglePlaceAutocompleteLibraryCommand($options['injector']['google_place_autocomplete']);
        }
    }

    /**
     * @param array $options
     */
    protected function registerGooglePlaceAutocompleteLibraryCommand($options = [])
    {
        $this->responseSubscriber->registerCommand($this->libraryCommand);
    }
}
