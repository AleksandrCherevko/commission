<?php declare(strict_types=1);

namespace Commission\Provider\BinList;

use App\Commission\Model\CardInfo;
use App\Commission\Provider\BinList\BinListProvider;
use App\Commission\Service\Serializer\SerializerService;
use App\Commission\Service\Validator\ValidatorService;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class BinListProviderTest extends TestCase
{
    public function testGetInfo(): void
    {
        $client = new Client([
            'handler' => HandlerStack::create(
                new MockHandler([
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        json_encode([
                            'country' => [
                                'alpha2' => 'DK',
                            ],
                        ]),
                    ),
                ])
            )
        ]);

        $binListProvider = new BinListProvider(
            new SerializerService(),
            new ValidatorService(),
            $client
        );

        $cardInfo = $binListProvider->getInfo('45717360');
        $this->assertInstanceOf(CardInfo::class, $cardInfo);
        $this->assertEquals('DK', $cardInfo->getCountry()->getAlpha2());
    }
}
