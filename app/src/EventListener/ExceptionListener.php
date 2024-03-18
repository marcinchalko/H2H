<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\InvalidArgument;
use App\Exception\InvalidJsonRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        if ($e instanceof InvalidJsonRequest) {
            $response = new JsonResponse(
                data: [
                    'message' => 'Invalid Request',
                    'errors' => $e->getErrors()
                ],
                status: Response::HTTP_BAD_REQUEST,
            );
            $event->setResponse($response);
        } elseif ($e instanceof InvalidArgument) {
            $response = new JsonResponse(
            data: [
                'message' => 'Invalid Argument',
                'errors' => $e->getErrors()
            ],
                status: Response::HTTP_BAD_REQUEST,
            );
            $event->setResponse($response);
        } elseif ($e instanceof \Throwable) {
            $response = new JsonResponse(
            data: [
                'message' => 'Internal Server Error',
                'errors' => $e->getMessage()
            ],
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
            $event->setResponse($response);
        }
    }
}