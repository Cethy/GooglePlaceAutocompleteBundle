Cethyworks\GooglePlaceAutocompleteBundle
===
Provides a Google Place Autocomplete Type, the most minimalist, unobtrusive way possible.

[![CircleCI](https://circleci.com/gh/Cethy/GooglePlaceAutocompleteBundle/tree/master.svg?style=shield)](https://circleci.com/gh/Cethy/GooglePlaceAutocompleteBundle/tree/master)


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

2\. Use `Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType` into your form ;
  
3\. That's it.


## How it works
When a `Cethyworks\GooglePlaceAutocompleteBundle\Form\SimpleGooglePlaceAutocompleteType` is used, 
it registers a listener (`Cethyworks\ContentInjectorBundle\Form\Listener\SimpleFormViewAwareListener`) on the `kernel.response` event 
which will inject the javascript code needed (with the input ids & the google api_key) into the `Response` automatically.


## Additional information
[Google Place Autocomplete Documentation](https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete)
