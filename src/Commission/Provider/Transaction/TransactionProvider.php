<?php

namespace App\Commission\Provider\Transaction;

use App\Commission\Exception\FileException;
use App\Commission\Model\Transaction;
use App\Commission\Service\Serializer\SerializerServiceInterface;
use App\Commission\Service\Validator\ValidatorServiceInterface;
use Generator;
use SplFileObject;

class TransactionProvider implements TransactionProviderInterface
{
    protected SerializerServiceInterface $serializer;

    protected ValidatorServiceInterface $validator;

    protected ?string $filePath;

    public function __construct(
        SerializerServiceInterface $serializer,
        ValidatorServiceInterface $validator,
        ?string $filePath
    )
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->filePath = $filePath;
    }

    /**
     * @return Generator|Transaction[]
     */
    public function getTransactions(): Generator
    {
        $file = $this->getFile();

        while (! $file->eof()) {
            yield $this->parse($file->fgets());
        }

        $file = null;
    }

    protected function parse(string $content): Transaction
    {
        $transaction = $this->serializer->deserialize($content, Transaction::class);
        $this->validator->validate($transaction);

        return $transaction;
    }

    protected function getFile(): SplFileObject
    {
        $this->validatePath();

        return new SplFileObject($this->getFilePath());
    }

    protected function validatePath(): void
    {
        if (! file_exists($this->getFilePath()) || ! is_file($this->getFilePath())) {
            $this->throwNotFundError($this->getFilePath());
        }
    }

    protected function getFilePath(): string
    {
        $path = realpath($this->filePath);

        if (! $path) {
            $this->throwNotFundError($this->filePath);
        }

        return $path;
    }

    protected function throwNotFundError(string $path): void
    {
        throw new FileException('Transactions file not found.', ['path' => $path]);
    }
}
