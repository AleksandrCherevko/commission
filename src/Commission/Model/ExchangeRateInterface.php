<?php

namespace App\Commission\Model;

interface ExchangeRateInterface
{
    public function getRates(): array;

    public function getByCurrency(string $currency): float;
}