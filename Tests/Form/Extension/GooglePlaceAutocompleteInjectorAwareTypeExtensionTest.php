<?php

namespace Cethyworks\GooglePlaceAutocompleteBundle\Tests\Form\Extension;

use Cethyworks\ContentInjectorBundle\EventSubscriber\ContentInjectorSubscriber;
use Cethyworks\GooglePlaceAutocompleteBundle\Command\GooglePlaceAutocompleteLibraryCommand;
use Cethyworks\GooglePlaceAutocompleteBundle\Form\Extension\GooglePlaceAutocompleteInjectorAwareTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GooglePlaceAutocompleteInjectorAwareTypeExtensionTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|GooglePlaceAutocompleteLibraryCommand
     */
    protected $libraryCommand;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ContentInjectorSubscriber
     */
    protected $subscriber;

    /**
     * @var GooglePlaceAutocompleteInjectorAwareTypeExtension
     */
    protected $extension;

    public function testGetExtendedType()
    {
        $this->assertEquals(FormType::class, $this->extension->getExtendedType());
    }

    public function dataTestConfigureOptions()
    {
        return [
            'injector disabled' => [
                ['injector' => false],
                ['injector' => false] ],

            'injector enabled, google_place_autocomplete disabled' => [
                ['injector' => ['google_place_autocomplete' => false]],
                ['injector' => ['google_place_autocomplete' => false]] ],
            'injector enabled, google_place_autocomplete enabled' => [
                ['injector' => ['google_place_autocomplete' => true]],
                ['injector' => ['google_place_autocomplete' => true]] ],

            'injector enabled, google_place_autocomplete disabled + additional data' => [
                ['injector' => ['google_place_autocomplete' => false, 'template' => 'foo']],
                ['injector' => ['google_place_autocomplete' => false, 'template' => 'foo']] ],
            'injector enabled, google_place_autocomplete enabled + additional data' => [
                ['injector' => ['google_place_autocomplete' => true, 'template' => 'foo']],
                ['injector' => ['google_place_autocomplete' => true, 'template' => 'foo']] ]
        ];
    }

    /**
     * @dataProvider dataTestConfigureOptions
     */
    public function testConfigureOptions($expectedResolvedOptions, $options)
    {
        $resolver = new OptionsResolver();

        // emulate InjectorAwareTypeExtension::configureOptions
        $resolver->setDefault('injector', false);
        $this->extension->configureOptions($resolver);

        $this->assertEquals($expectedResolvedOptions, $resolver->resolve($options));
    }

    public function dataTestBuildViewShouldNotRegisterCommand()
    {
        return [
            [ ['injector' => false] ],
            [ ['injector' => ['google_place_autocomplete' => false]] ]
        ];
    }

    /**
     * @dataProvider dataTestBuildViewShouldNotRegisterCommand
     */
    public function testBuildViewShouldNotRegisterCommand($options)
    {
        $view = new FormView();
        $form = $this->getMockBuilder(FormInterface::class)->disableOriginalConstructor()->getMock();

        $this->subscriber->expects($this->never())->method('registerCommand');

        $this->extension->buildView($view, $form, $options);
    }

    public function testBuildViewShouldRegisterCommand()
    {
        $options = ['injector' => ['google_place_autocomplete' => true]];

        $view = new FormView();
        $form = $this->getMockBuilder(FormInterface::class)->disableOriginalConstructor()->getMock();

        $this->subscriber->expects($this->once())->method('registerCommand')->with($this->libraryCommand);

        $this->extension->buildView($view, $form, $options);
    }

    protected function setUp()
    {
        $this->libraryCommand = $this->getMockBuilder(GooglePlaceAutocompleteLibraryCommand::class)->disableOriginalConstructor()->getMock();
        $this->subscriber = $this->getMockBuilder(ContentInjectorSubscriber::class)->disableOriginalConstructor()->getMock();

        $this->extension = new GooglePlaceAutocompleteInjectorAwareTypeExtension($this->subscriber, $this->libraryCommand);
    }
}
