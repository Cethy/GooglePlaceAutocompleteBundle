<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Functional;

use Cethyworks\ContentInjectorBundle\Form\Listener\SimpleFormViewAwareListener;
use Cethyworks\ContentInjectorBundle\Registerer\ListenerRegisterer;
use Cethyworks\ContentInjectorBundle\Test\FormViewAwareInjectorTypeTestCaseHelper;
use Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType;
use Cethyworks\GooglePlaceAutocompleteBundle\Tests\Dummy\DummyKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SimpleGooglePlaceAutocompleteTypeFunctionalTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    public function testJsCodeInjected()
    {
        $expectedCodeInjected = //file_get_contents(__DIR__ . '../../Resources/assets/twig/simple_google_place_autocomplete_js.html.twig');
            <<<EOF
<script>
  function initMap() {
    var inputs = ["simple_google_place_autocomplete"];

    for(var i=0;i<inputs.length;i++) {
      var input = /** @type {!HTMLInputElement} */(
        document.getElementById(inputs[i])
      );

      new google.maps.places.Autocomplete(input);
    }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=foo_api_key"
        async defer></script>

EOF;

        /** @var EventDispatcherInterface $dispatcher */
        // do not use the container dispatcher because it throws a "LogicException: Event "__section__" is not started."
        // at event dispatch, exception which I do not understand :)
        $dispatcher = new EventDispatcher();//$this->container->get('event_dispatcher');

        /** @var \Cethyworks\ContentInjectorBundle\Registerer\ListenerRegisterer $registerer */
        $registerer = new ListenerRegisterer($dispatcher);//$this->container->get('cethyworks_content_injector.listener.registerer');

        $listener = $this->container->get('cethyworks.simple_google_place_autocomplete.listener.factory')->createListener(
            SimpleFormViewAwareListener::class, '@CethyworksGooglePlaceAutocompleteBundle/Resources/assets/twig/simple_google_place_autocomplete_js.html.twig'
        );

        $formFactory    = Forms::createFormFactoryBuilder()
            ->addExtensions([
                // register the type instances with the PreloadedExtension
                new PreloadedExtension(FormViewAwareInjectorTypeTestCaseHelper::buildPreloadedTypes(
                    [SimpleGooglePlaceAutocompleteType::class],
                    $registerer,
                    $listener
                ), []),
            ])
            ->getFormFactory()
        ;


        /** @var Form $form */
        $form     = $formFactory->create(SimpleGooglePlaceAutocompleteType::class);
        $formView = $form->createView();


        // trigger kernel.response
        $response = new Response('<body>foo</body>bar');
        $event = $this->createFilterResponseEvent($response);


        $dispatcher->dispatch('kernel.response', $event);

        $this->assertContains($expectedCodeInjected, $response->getContent());
    }

    /**
     * @return FilterResponseEvent
     */
    protected function createFilterResponseEvent(Response $response)
    {
        return new FilterResponseEvent(self::$kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );
    }

    /**
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        if (null === static::$class) {
            static::$class = DummyKernel::class;
        }

        return new static::$class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}
