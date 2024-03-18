<?php 

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CollectionResponse extends JsonResponse
{

    public function __construct(bool $more, array $data)
    {
        return parent::__construct([
                'more' => $more, 
                'data' => $data
            ], 
            empty($data) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK
        );
    }
}