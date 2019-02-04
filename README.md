Cethyworks\GooglePlaceAutocompleteBundle
===
Provides a Google Place Autocomplete Type, the most minimalist, unobtrusive way possible.

[![CircleCI](https://circleci.com/gh/Cethy/GooglePlaceAutocompleteBundle/tree/master.svg?style=shield)](https://circleci.com/gh/Cethy/GooglePlaceAutocompleteBundle/tree/master)

## Versions
### For symfony >= 3.4
Use latest or >= v3.2

### For symfony < 3.4
Use 2.2 or lower


## Install

1\. Composer require

    $ composer require cethyworks/google-place-autocomplete-bundle

2\. Register bundles

    // AppKernel.php
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = [
                // ...
                new Cethyworks\ContentInjectorBundle\CethyworksContentInjectorBundle(),
                new Cethyworks\GooglePlaceAutocompleteBundle\CethyworksGooglePlaceAutocompleteBundle(),
            ];
            // ...


## How to use
1\. Update (optionally) your `config.yml` with :

    cethyworks_google_place_autocomplete:
        google:
            api_key: 'your_api_key'

2\. Use `Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType` into your forms ;
  
3\. Done !


## Get more data from the Google Place API
If you need more info from the place API results, you can use the `ComplexGooglePlaceAutocompleteType` in your forms instead.

Instead of returning a simple `string`, this Type return a `Cethyworks\GooglePlaceAutocompleteBundle\Model\Place` object.   

In order to persist it, the bundle provides doctrine mapping, use it like this in your entities :

    use Cethyworks\GooglePlaceAutocompleteBundle\Model\Place;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Table(name="dummy_entity")
     * @ORM\Entity()
     */
    class DummyEntity
    {
        /**
         * @var Place
         *
         * @ORM\Embedded(class="Cethyworks\GooglePlaceAutocompleteBundle\Model\Place")
         *
         * @Assert\NotBlank()
         */
        private $locationAddress;
        
        // ...
    }

## How it works
When either a `SimpleGooglePlaceAutocompleteType` or a `ComplexGooglePlaceAutocompleteType` are used, 
it registers 2 [`InjectorCommands`](https://github.com/Cethy/ContentInjectorBundle#cethyworkscontentinjectorbundle) (one for the library call, one for the input controls) 
which will inject the necessary javascript code (with the input id & the google api_key) into the `Response` automatically.

## Additional information
[Cethyworks\ContentInjectorBundle](https://github.com/Cethy/ContentInjectorBundle)

[Google Place Autocomplete Documentation](https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete)
