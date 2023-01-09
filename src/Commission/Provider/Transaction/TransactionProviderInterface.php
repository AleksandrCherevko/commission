<?php

namespace App\Commission\Provider\Transaction;

use App\Commission\Model\Transaction;
use Generator;

interface TransactionProviderInterface
{
    /**
     * @return Generator|Transaction[]
     */
    public function getTransactions(): Generator;
}
