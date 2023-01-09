<?php

namespace App\Commission\Service\Validator;

interface ValidatorServiceInterface
{
    public function validate($model): void;
}
