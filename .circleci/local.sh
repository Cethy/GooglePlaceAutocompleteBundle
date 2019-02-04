#/bin/sh
export CIRCLE_TOKEN=f241ae4c90ad683d5d450e3a1a397d4aa63a21fd

curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form config=@config.yml \
    --form notify=false \
        https://circleci.com/api/v1.1/project/github/Cethy/GooglePlaceAutocompleteBundle/tree/fix_for_symfony4
