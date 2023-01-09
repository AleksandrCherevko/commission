<?php

namespace App\Commission\Provider\BinList;

use App\Commission\Exception\RequestException;
use App\Commission\Model\CardInfo;
use App\Commission\Service\Serializer\SerializerServiceInterface;
use App\Commission\Service\Validator\ValidatorServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;

class BinListProvider implements BinListProviderInterface
{
    protected const URL = 'https://lookup.binlist.net/';

    protected SerializerServiceInterface $serializer;

    protected ValidatorServiceInterface $validator;

    protected ClientInterface $client;

    public function __construct(
        SerializerServiceInterface $serializer,
        ValidatorServiceInterface $validator,
        ClientInterface $client
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->client = $client;
    }

    public function getInfo(string $bin): CardInfo
    {
        return $this->parse($this->get($bin));
    }

    protected function parse(string $content): CardInfo
    {
        $bin = $this->serializer->deserialize($content, CardInfo::class);
        $this->validator->validate($bin);

        return $bin;
    }

    protected function get(string $bin): string
    {
        try {
            $response = $this->client->get(
                self::URL . $bin,
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => 'text/plain',
                    ],
                ]
            );
        } catch (ClientException $clientException) {
            throw new RequestException(
                'BinListProvider request error.',
                [
                    'status_code' => $clientException->getResponse()->getStatusCode(),
                    'response' => $clientException->getResponse()->getBody()->getContents(),
                ]
            );
        }

        return $response->getBody()->getContents();
    }
}
