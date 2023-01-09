<?php

namespace App\Commission\Service\Validator;

use App\Commission\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService implements ValidatorServiceInterface
{
    protected ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    public function validate($model): void
    {
        $this->checkErrors(
            $this->validator->validate($model)
        );
    }

    protected function checkErrors(ConstraintViolationListInterface $list): void
    {
         if ($list->count()) {
             throw new ValidationException('Validation error.', $this->serializeErrors($list));
         }
    }

    private function serializeErrors(ConstraintViolationListInterface $list): array
    {
        $errors = [];

        foreach ($list as $error) {
            $errors[] = [
                'message' => $error->getMessage(),
                'property' => $error->getPropertyPath(),
            ];
        }

        return $errors;
    }
}
