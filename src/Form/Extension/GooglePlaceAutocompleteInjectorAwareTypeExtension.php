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
     * Kept and modified for Symfony < 4.2, its deprecated and replaced by static function below.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return self::getExtendedTypes()[0];
    }

    /**
     * Gets the extended types - not implementing it is deprecated since Symfony 4.2.
     *
     * @return array The name of the type being extended
     */
    public static function getExtendedTypes()
    {
        return [FormType::class];
    }

    /**
     * Update injector options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('injector_google_place_autocomplete', false);

        $resolver->setNormalizer('injector_google_place_autocomplete', function (Options $options, $injectorOption) {
            // injector not enabled
            if(! $injectorOption) {
                return false;
            }
            return $injectorOption;
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if(isset($options['injector']) && $options['injector'] && $options['injector_google_place_autocomplete']) {
            $this->registerGooglePlaceAutocompleteLibraryCommand($options['injector_google_place_autocomplete']);
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
