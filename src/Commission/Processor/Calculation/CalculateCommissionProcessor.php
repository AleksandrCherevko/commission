<?php

namespace App\Commission\Processor\Calculation;

use App\Commission\Helper\CountryHelper;
use App\Commission\Provider\BinList\BinListProviderInterface;
use App\Commission\Provider\ExchangeRate\ExchangeRateProviderInterface;
use App\Commission\Provider\Transaction\TransactionProviderInterface;
use Generator;

class CalculateCommissionProcessor implements CalculationProcessorInterface
{
    protected TransactionProviderInterface $transactionProvider;

    protected BinListProviderInterface $binListProvider;

    protected ExchangeRateProviderInterface $exchangeRateProvider;

    public function __construct(
        TransactionProviderInterface  $transactionProvider,
        BinListProviderInterface      $binProvider,
        ExchangeRateProviderInterface $exchangeRateProvider
    )
    {
        $this->transactionProvider = $transactionProvider;
        $this->binListProvider = $binProvider;
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    /**
     * @return Generator|float[]
     */
    public function calculate(): Generator
    {
        $exchangeRates = $this->exchangeRateProvider->get();

        foreach ($this->transactionProvider->getTransactions() as $transaction) {

            $cardInfo = $this->binListProvider->getInfo($transaction->getBin());
            $rate = $exchangeRates->getByCurrency($transaction->getCurrency());
            $isEuCountry = CountryHelper::isEU($cardInfo->getCountry()->getAlpha2());

            $amountEUR = $this->convertToEUR($transaction->getAmount(), $transaction->getCurrency(), $rate);
            $commission = $amountEUR * $this->getCommissionRate($isEuCountry);

            yield $this->ceilCents($commission);
        }
    }

    protected function convertToEUR(float $amount, string $currency, float $rate): float
    {
        if ($currency !== 'EUR' && $rate > 0) {
            $amount = $amount / $rate;
        }

        return $amount;
    }

    protected function ceilCents(float $amount): float
    {
        return ceil($amount * 100) / 100;
    }

    protected function getCommissionRate(bool $isEuCountry): float
    {
        return $isEuCountry ? 0.01 : 0.02;
    }
}
