<?php

namespace App\Commission\Provider\ExchangeRate;

use App\Commission\Model\ExchangeRateInterface;

interface ExchangeRateProviderInterface
{
    public function get(): ExchangeRateInterface;
}
