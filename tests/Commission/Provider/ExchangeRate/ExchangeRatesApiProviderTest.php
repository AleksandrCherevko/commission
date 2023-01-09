<?php declare(strict_types=1);

namespace Commission\Provider\ExchangeRate;

use App\Commission\Model\ExchangeRate;
use App\Commission\Provider\ExchangeRate\ExchangeRatesApiProvider;
use App\Commission\Service\Serializer\SerializerService;
use App\Commission\Service\Validator\ValidatorService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ExchangeRatesApiProviderTest extends TestCase
{
    public function testGet(): void
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                json_encode([
                    'rates' => [
                        'EUR' => 1,
                        'USD' => 1.06452,
                        'JPY' => 140.622085,
                    ],
                    'success' => true,
                ]),
            ),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $rateProvider = new ExchangeRatesApiProvider(
            new SerializerService(),
            new ValidatorService(),
            $client,
            'access_key'
        );

        $rates = $rateProvider->get();
        $this->assertInstanceOf(ExchangeRate::class, $rates);
        $this->assertArrayHasKey('EUR', $rates->getRates());
        $this->assertArrayHasKey('USD', $rates->getRates());
        $this->assertArrayHasKey('JPY', $rates->getRates());
    }
}
