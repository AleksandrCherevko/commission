<?php declare(strict_types=1);

namespace Commission\Provider\Transaction;

use App\Commission\Exception\FileException;
use App\Commission\Exception\SerializeException;
use App\Commission\Exception\ValidationException;
use App\Commission\Model\Transaction;
use App\Commission\Provider\Transaction\TransactionProvider;
use App\Commission\Service\Serializer\SerializerService;
use App\Commission\Service\Validator\ValidatorService;
use PHPUnit\Framework\TestCase;

class TransactionProviderTest extends TestCase
{
    public function testGetTransactionsInvalidFilePath(): void
    {
        $this->expectException(FileException::class);

        (new TransactionProvider(
            new SerializerService(),
            new ValidatorService(),
            null
        ))
            ->getTransactions()->current();
    }

    public function testTransactionValidation(): void
    {
        $this->expectException(ValidationException::class);

        (new TransactionProvider(
            new SerializerService(),
            new ValidatorService(),
            __DIR__.'/../../../fixtures/transactions_invalid_values.txt'
        ))
            ->getTransactions()->current();
    }

    public function testTransactionsInvalidFileStructure(): void
    {
        $this->expectException(SerializeException::class);

        (new TransactionProvider(
            new SerializerService(),
            new ValidatorService(),
            __DIR__.'/../../../fixtures/transactions_invalid_structure.txt'
        ))
            ->getTransactions()->current();
    }

    public function testTransactionsEmptyFile(): void
    {
        $this->expectException(SerializeException::class);

        (new TransactionProvider(
            new SerializerService(),
            new ValidatorService(),
            __DIR__.'/../../../fixtures/empty.txt'
        ))
            ->getTransactions()->current();
    }

    public function testGetTransactions(): void
    {
        $transactionProvider = new TransactionProvider(
            new SerializerService(),
            new ValidatorService(),
            __DIR__.'/../../../fixtures/transactions.txt'
        );

        $transactionCount = 0;
        foreach ($transactionProvider->getTransactions() as $transaction) {
            $this->assertInstanceOf(Transaction::class, $transaction);
            $this->assertContains($transaction->getBin(), ['45717360', '516793', '45417360']);
            $transactionCount++;
        }

        $this->assertEquals(3, $transactionCount);
    }
}
