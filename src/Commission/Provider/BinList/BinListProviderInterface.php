<?php

namespace App\Commission\Provider\BinList;

use App\Commission\Model\CardInfo;

interface BinListProviderInterface
{
    public function getInfo(string $bin): CardInfo;
}
