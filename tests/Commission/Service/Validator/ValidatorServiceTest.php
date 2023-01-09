<?php declare(strict_types=1);

namespace Commission\Service\Validator;

use App\Commission\Exception\ValidationException;
use App\Commission\Model\Transaction;
use App\Commission\Service\Validator\ValidatorService;
use PHPUnit\Framework\TestCase;

class ValidatorServiceTest extends TestCase
{
    public function testValidate(): void
    {
        $this->expectException(ValidationException::class);

        $validator = new ValidatorService();
        $transaction = new Transaction();

        $validator->validate($transaction);
    }
}
