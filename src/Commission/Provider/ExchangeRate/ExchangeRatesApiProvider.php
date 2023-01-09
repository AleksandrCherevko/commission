<?php

namespace App\Commission\Provider\ExchangeRate;

use GuzzleHttp\RequestOptions;

class ExchangeRatesApiProvider extends AbstractRateApiProvider
{
    protected const URL = 'https://api.exchangeratesapi.io/latest';

    protected function getUrl(): string
    {
        return sprintf('%s?%s', self::URL, http_build_query(['access_key' => $this->apiKey]));
    }

    protected function getHeaders(): array
    {
        return [
            RequestOptions::HEADERS => [
                'Content-Type' => 'text/plain',
            ],
        ];
    }
}
