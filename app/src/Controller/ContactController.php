<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\ContactGetMessage;
use App\Request\ContactSaveMessage;
use App\Response\CollectionResponse;
use App\Response\PersistResponse;
use App\Service\ContactMessageService;
use App\Service\SerializationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    private $contactMessageService;
    private $serializationService;

    public function __construct(ContactMessageService $contactMessageService, SerializationService $serializationService)
    {
        $this->contactMessageService = $contactMessageService;
        $this->serializationService = $serializationService;
    }

    public function saveMessage(ContactSaveMessage $request): JsonResponse
    {
        $this->serializationService->serialize(
            $this->contactMessageService->save($request->getContent()), 
            ['ContactMessage']
        );

        return new PersistResponse($this->serializationService->toArray());
    }

    public function getMessages(ContactGetMessage $request): JsonResponse
    {
        $pagerfanta = $this->contactMessageService->get(
            $request->getRequest()->query->getInt('page', 1), 
            $request->getRequest()->query->getInt('per_page', 10)
        );

        $this->serializationService->serialize($pagerfanta->getCurrentPageResults(), ['ContactMessage']);

        return new CollectionResponse($pagerfanta->haveToPaginate(), $this->serializationService->toArray());
    }

    public function getMessagesElastica(ContactGetMessage $request): JsonResponse
    {
        $pagination = $this->contactMessageService->getElsatica(
            $request->getRequest()->query->getInt('page', 1), 
            $request->getRequest()->query->getInt('per_page', 10)
        );

        $this->serializationService->serialize($pagination->getItems(), ['ContactMessage']);

        return new CollectionResponse((
            $pagination->getPaginationData()['current'] < $pagination->getPaginationData()['last']), 
            $this->serializationService->toArray()
        );
    }

    public function getMessagesRedis(ContactGetMessage $request): JsonResponse
    {
        $pagination = $this->contactMessageService->getRepository(
            $request->getRequest()->query->getInt('page', 1), 
            $request->getRequest()->query->getInt('per_page', 10)
        );

        $this->serializationService->serialize($pagination->getItems(), ['ContactMessage']);

        return new CollectionResponse((
            $pagination->getPaginationData()['current'] < $pagination->getPaginationData()['last']), 
            $this->serializationService->toArray());
    }

    public function getMessagesRedisCache(ContactGetMessage $request): JsonResponse
    {
        $pagination = $this->contactMessageService->getRepository(
            $request->getRequest()->query->getInt('page', 1), 
            $request->getRequest()->query->getInt('per_page', 10)
        );

        $this->serializationService->serialize($pagination->getItems(), ['ContactMessage']);

        return new CollectionResponse((
            $pagination->getPaginationData()['current'] < $pagination->getPaginationData()['last']), 
            $this->serializationService->toArray());
    }

    public function getMessagesCache(ContactGetMessage $request): JsonResponse
    {
        return new CollectionResponse(
            false, 
            $this->contactMessageService->getRepositorySimpleCache()
        );
    }
}
