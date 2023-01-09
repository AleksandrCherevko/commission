<?php

namespace App\Commission\Model;

use App\Commission\Exception\CommissionException;
use Symfony\Component\Validator\Constraints as Assert;

class ExchangeRate implements ExchangeRateInterface
{
    /**
     * @var array
     *
     * @Assert\NotBlank
     */
    protected array $rates = [];

    /**
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    /**
     * @param  array        $rates
     * @return self
     */
    public function setRates(array $rates): self
    {
        $this->rates = $rates;

        return $this;
    }

    public function getByCurrency(string $currency): float
    {
        if (! isset($this->getRates()[$currency])) {
            throw new CommissionException(sprintf('Exchange rate for currency "%s" not found', $currency));
        }

        return $this->getRates()[$currency];
    }
}
