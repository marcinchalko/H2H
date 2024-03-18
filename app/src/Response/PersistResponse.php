<?php 

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PersistResponse extends JsonResponse
{

    public function __construct(array $data) 
    {
        return parent::__construct(['message' => 'Created', 'data' => $data], Response::HTTP_CREATED);
    }
}