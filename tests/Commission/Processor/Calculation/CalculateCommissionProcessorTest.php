<?php declare(strict_types=1);

namespace Commission\Processor\Calculation;

use App\Commission\Processor\Calculation\CalculateCommissionProcessor;
use App\Commission\Provider\BinList\BinListProvider;
use App\Commission\Provider\ExchangeRate\ApiLayerExchangeRateProvider;
use App\Commission\Provider\Transaction\TransactionProvider;
use App\Commission\Service\Serializer\SerializerService;
use App\Commission\Service\Validator\ValidatorService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CalculateCommissionProcessorTest extends TestCase
{
    public function testCalculate(): void
    {
        $rateClient = new Client([
            'handler' => HandlerStack::create(
                new MockHandler([
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
                ])
            ),
        ]);

        $binListClient = new Client([
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
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        json_encode([
                            'country' => [
                                'alpha2' => 'LT',
                            ],
                        ]),
                    ),
                    new Response(
                        200,
                        ['Content-Type' => 'application/json; charset=utf-8'],
                        json_encode([
                            'country' => [
                                'alpha2' => 'JP',
                            ],
                        ]),
                    ),
                ])
            )
        ]);

        $exchangeRateApiKey = 'aZKGaxRKLE0e2vhdg6uJGQ2BGCJ42CVe';
        $serializer = new SerializerService();
        $validator = new ValidatorService();

        $calculateProcessor = new CalculateCommissionProcessor(
            new TransactionProvider(
                $serializer,
                $validator,
                __DIR__.'/../../../fixtures/transactions.txt'
            ),
            new BinListProvider(
                $serializer,
                $validator,
                $binListClient
            ),
            new ApiLayerExchangeRateProvider(
                $serializer,
                $validator,
                $rateClient,
                $exchangeRateApiKey
            )
        );

        $commissionsResult = [];
        foreach ($calculateProcessor->calculate() as $commission) {
            $commissionsResult[] = $commission;
        }

        $this->assertCount(3, $commissionsResult);
        $this->assertContains(1.00, $commissionsResult);
        $this->assertContains(0.47, $commissionsResult);
        $this->assertContains(1.43, $commissionsResult);
    }
}
