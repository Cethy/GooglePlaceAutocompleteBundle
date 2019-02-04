<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Functional;

use Cethyworks\GooglePlaceAutocompleteBundle\Form\ComplexGooglePlaceAutocompleteType;
use Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType;
use Cethyworks\GooglePlaceAutocompleteBundle\Tests\Functional\Mock\MockKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FormInjectionFunctionalTest extends WebTestCase
{
    public function setUp()
    {
        self::bootKernel();

        //self::$container = self::$kernel->getContainer();
    }

    public function dataTestDisplayFormWithInjection()
    {
        $simpleResponseContent =
            <<<EOF
<body>foo<script>
  function initMapAutocomplete() {
    var inputId = "simple_google_place_autocomplete";
    var input = /** @type {!HTMLInputElement} */(document.getElementById(inputId));

    new google.maps.places.Autocomplete(input);
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMapAutocomplete&key=foo_api_key"
        async defer></script>
</body>bar
EOF;
        $complexResponseContent =
            <<<EOF
<body>foo<script>
  function initMapAutocomplete() {
    var formId = "complex_google_place_autocomplete";

    var userInput = /** @type {!HTMLInputElement} */(document.getElementById(formId +'_autocomplete'));

    var inputName              = document.getElementById(formId +'_name');
    var inputAddressComponents = document.getElementById(formId +'_address_components');

    var autocomplete = new google.maps.places.Autocomplete(userInput);
    var placeService = new google.maps.places.PlacesService(userInput);

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();

      inputName.value              = place.name;
      inputAddressComponents.value = JSON.stringify(place.address_components);

      // try to clean name
      if(place.place_id) {
        placeService.getDetails({placeId: place.place_id}, function (result) {
          inputName = result.name;
        });
      }
    });
  }
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMapAutocomplete&key=foo_api_key"
        async defer></script>
</body>bar
EOF;

        return [
            'simple form' => [SimpleGooglePlaceAutocompleteType::class, $simpleResponseContent],
            'complex form' => [ComplexGooglePlaceAutocompleteType::class, $complexResponseContent]
        ];
    }

    /**
     * @dataProvider dataTestDisplayFormWithInjection
     */
    public function testDisplayFormWithInjection($typeClass, $expectedResponseContent)
    {
        // debug=false to be able to use the dispatcher
        // using debug=true, the container dispatcher throws a "LogicException: Event "__section__" is not started." at event dispatch
        $kernel = static::bootKernel(['debug' => false]);
        $container = $kernel->getContainer();

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $container->get('event_dispatcher');

        /** @var FormFactoryInterface $formFactory */
        $formFactory = $container->get('form.factory');

        $form = $formFactory->create($typeClass);
        $formView = $form->createView();

        // trigger kernel.response
        $response = new Response('<body>foo</body>bar');
        $event = new FilterResponseEvent(self::$kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response);

        $dispatcher->dispatch('kernel.response', $event);



        $this->assertEquals($expectedResponseContent, $response->getContent());
    }

    /**
     * @return KernelInterface A KernelInterface instance
     */
    protected static function createKernel(array $options = array())
    {
        if (null === static::$class) {
            static::$class = MockKernel::class;
        }
        return new static::$class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }
}
