<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Email;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class EmailHandler
{

    public function __invoke(Email $message): void
    {
        echo 'Sending notify on email: ' . $message->email;
    }
}
