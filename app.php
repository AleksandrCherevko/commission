<?php declare(strict_types=1);

use App\Commission\Commission;
use App\Commission\Processor\Calculation\CalculateCommissionProcessor;
use App\Commission\Provider\BinList\BinListProvider;
use App\Commission\Provider\ExchangeRate\ApiLayerExchangeRateProvider;
use App\Commission\Provider\Transaction\TransactionProvider;
use App\Commission\Service\Serializer\SerializerService;
use App\Commission\Service\Validator\ValidatorService;
use GuzzleHttp\Client;

require __DIR__.'/vendor/autoload.php';

/**
 * Working api
 * api key for https://api.apilayer.com/exchangerates_data/latest
 * App\Commission\Provider\ExchangeRate\ApiLayerExchangeRateProvider
 */
$apiLayerExchangeRatApiKey = 'SHOULD_BE_PROVIDED';
/**
 * Not working for me, with free api key
 * access key for https://api.exchangeratesapi.io/latest
 * App\Commission\Provider\ExchangeRate\ExchangeRatesApiProvider
 */
$accessKey = 'SHOULD_BE_PROVIDED';

$filePath = $argv[1] ?? null;
$serializer = new SerializerService();
$validator = new ValidatorService();
$client = new Client();

(new Commission(
    new CalculateCommissionProcessor(
        new TransactionProvider(
            $serializer,
            $validator,
            $filePath
        ),
        new BinListProvider(
            $serializer,
            $validator,
            $client
        ),
        new ApiLayerExchangeRateProvider(
            $serializer,
            $validator,
            $client,
            $apiLayerExchangeRatApiKey
        )
    )
))->calculate();
