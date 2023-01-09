<?php

namespace App\Commission\Provider\ExchangeRate;

use GuzzleHttp\RequestOptions;

class ApiLayerExchangeRateProvider extends AbstractRateApiProvider
{
    protected const URL = 'https://api.apilayer.com/exchangerates_data/latest';

    protected function getUrl(): string
    {
        return self::URL;
    }

    protected function getHeaders(): array
    {
        return [
            RequestOptions::HEADERS => [
                'Content-Type' => 'text/plain',
                'apikey' => $this->apiKey,
            ],
        ];
    }
}
