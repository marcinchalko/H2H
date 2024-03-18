<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class InvalidArgument extends RuntimeException
{
    public function __construct(protected readonly array $errors = [])
    {
        parent::__construct();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}