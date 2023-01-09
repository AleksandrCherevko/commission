<?php

namespace App\Commission\Exception;

use LogicException;
use Throwable;

class CommissionException extends LogicException
{
    protected array $data;

    public function __construct($message = "", array $data = [], $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
