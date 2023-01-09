<?php

namespace App\Commission\Processor\Calculation;

use Generator;

interface CalculationProcessorInterface
{
    /**
     * @return Generator|float[]
     */
    public function calculate(): Generator;
}
