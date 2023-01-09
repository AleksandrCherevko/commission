<?php

namespace App\Commission\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Transaction
{
    /**
     * @var string|integer|null
     *
     * @Assert\NotBlank
     */
    protected ?string $bin;

    /**
     * @var string|float|integer|null
     *
     * @Assert\NotBlank
     */
    protected ?float $amount;

    /**
     * @var string|null
     *
     * @Assert\NotBlank
     */
    protected ?string $currency;

    /**
     * @return string
     *
     */
    public function getBin(): string
    {
        return $this->bin;
    }

    public function setBin(?string $bin): self
    {
        $this->bin = $bin;

        return $this;
    }

    public function getAmount(): float
    {
        return floatval($this->amount);
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
