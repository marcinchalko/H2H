<?php

declare(strict_types=1);

namespace App\Message;

final class Email
{
    public function __construct(public string $email)
    {
    }
}
