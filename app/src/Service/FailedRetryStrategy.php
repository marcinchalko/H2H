<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Messenger\Retry\RetryStrategyInterface;
use Symfony\Component\Messenger\Envelope;

class FailedRetryStrategy implements RetryStrategyInterface
{
    public function isRetryable(Envelope $message, \Throwable|null $throwable = null): bool
    {
        return true;
    }
    public function getWaitingTime(Envelope $message, \Throwable|null $throwable = null): int
    {
        return 1000;
    }
}