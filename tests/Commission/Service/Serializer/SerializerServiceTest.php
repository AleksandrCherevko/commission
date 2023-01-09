<?php declare(strict_types=1);

namespace Commission\Service\Serializer;

use App\Commission\Model\Transaction;
use App\Commission\Service\Serializer\SerializerService;
use PHPUnit\Framework\TestCase;

class SerializerServiceTest extends TestCase
{
    public function testDeserializeSuccess(): void
    {
        $serializer = new SerializerService();
        $data = json_encode([
            'bin' => '45717360',
            'amount' => '100.00',
            'currency' => 'EUR',
        ]);

        $transaction = $serializer->deserialize($data, Transaction::class);
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('45717360', $transaction->getBin());
    }
}
