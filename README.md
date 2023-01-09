## [Commission module]()

    Should provide api keys for exchange rate api in app.php file

### Working api
  - api key for https://api.apilayer.com/exchangerates_data/latest
  - App\Commission\Provider\ExchangeRate\ApiLayerExchangeRateProvider
    > $apiLayerExchangeRatApiKey = 'SHOULD_BE_PROVIDED';

### Not working for me, with free api key
  - access key for https://api.exchangeratesapi.io/latest
  - App\Commission\Provider\ExchangeRate\ExchangeRatesApiProvider
    > $accessKey = 'SHOULD_BE_PROVIDED';