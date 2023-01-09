<?php

namespace App\Commission\Provider\ExchangeRate;

use App\Commission\Exception\RequestException;
use App\Commission\Model\ExchangeRate;
use App\Commission\Model\ExchangeRateInterface;
use App\Commission\Service\Serializer\SerializerServiceInterface;
use App\Commission\Service\Validator\ValidatorServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

abstract class AbstractRateApiProvider implements ExchangeRateProviderInterface
{
    protected SerializerServiceInterface $serializer;

    protected ValidatorServiceInterface $validator;

    protected ClientInterface $client;

    protected string $apiKey;

    public function __construct(
        SerializerServiceInterface $serializer,
        ValidatorServiceInterface $validator,
        ClientInterface $client,
        string $apiKey
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function get(): ExchangeRateInterface
    {
        return $this->getModel($this->getData());
    }

    protected function getModel(string $content): ExchangeRateInterface
    {
        $exchangeRate = $this->serializer->deserialize($content, ExchangeRate::class);
        $this->validator->validate($exchangeRate);

        return $exchangeRate;
    }

    protected function getData(): string
    {
        try {
            $response = $this->client->get(
                $this->getUrl(),
                $this->getHeaders()
            );
        } catch (ClientException $clientException) {
            throw new RequestException(
                sprintf('%s request error.', static::class),
                [
                    'status_code' => $clientException->getResponse()->getStatusCode(),
                    'response' => $clientException->getResponse()->getBody()->getContents(),
                ]
            );
        }

        if (json_decode($response->getBody()->getContents(), true)['success'] !== true) {
            $response->getBody()->rewind();
            throw new RequestException(
                sprintf('%s request error.', static::class),
                [
                    'status_code' => $response->getStatusCode(),
                    'response' => $response->getBody()->getContents(),
                ]
            );
        }
        $response->getBody()->rewind();

        return $response->getBody()->getContents();
    }

    abstract protected function getUrl(): string;

    abstract protected function getHeaders(): array;
}
