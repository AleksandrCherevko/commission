<?php

namespace App\Commission;

use App\Commission\Exception\CommissionException;
use App\Commission\Processor\Calculation\CalculationProcessorInterface;
use Throwable;

class Commission
{
    protected CalculationProcessorInterface $calculationProcessor;

    public function __construct(
        CalculationProcessorInterface $calculationProcessor
    )
    {
        $this->calculationProcessor = $calculationProcessor;
    }

    public function calculate(): void
    {
        try {
            foreach ($this->calculationProcessor->calculate() as $commission) {
                $this->print($commission);
            }
        } catch (Throwable $exception) {
            $this->printError($exception);
        }
    }

    protected function print(float $value): void
    {
        echo $value . PHP_EOL;
    }

    protected function printError(Throwable $exception): void
    {
        echo sprintf(
            'ERROR: %s; DATA: %s%s',
            $exception->getMessage(),
            $exception instanceof CommissionException ? json_encode($exception->getData()) : '',
            PHP_EOL
        );
    }
}
