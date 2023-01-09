<?php

namespace App\Commission\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Country
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank
     */
    protected ?string $alpha2;

    /**
     * @return string|null
     */
    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    /**
     * @param  string|null $alpha2
     * @return self
     */
    public function setAlpha2(?string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }
}
