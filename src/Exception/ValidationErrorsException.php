<?php
declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationErrorsException extends Exception
{
    public ConstraintViolationListInterface $errorConstraints;

    public function __construct(ConstraintViolationListInterface $errorConstraints)
    {
        $this->errorConstraints = $errorConstraints;

        parent::__construct('Validation error', 400);
    }
}
