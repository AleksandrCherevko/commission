<?php

namespace App\Commission\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CardInfo
{
    /**
     * @var Country|null
     *
     * @Assert\NotBlank
     */
    protected Country $country;

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }

    /**
     * @param  Country  $country
     * @return self
     */
    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }
}
